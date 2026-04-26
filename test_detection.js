const { chromium, devices } = require('playwright');

(async () => {
  const browser = await chromium.launch();
  const context = await browser.newContext({
    ...devices['iPhone 12 Pro'],
    viewport: { width: 390, height: 844 }
  });

  const page = await context.newPage();

  await page.goto('https://new.apertureleadership.com/posts?v=7');
  await page.waitForLoadState('networkidle');

  // Scroll to guideline-5 position
  await page.evaluate(() => window.scrollTo(0, 5092));
  await page.waitForTimeout(1000);

  // Check what the scroll spy would detect
  const detectionInfo = await page.evaluate(() => {
    const sections = document.querySelectorAll('.guideline-card');
    const scrollPos = window.scrollY + 120;
    const results = [];

    sections.forEach(section => {
      const sectionTop = section.offsetTop;
      const sectionHeight = section.offsetHeight;
      const sectionId = section.getAttribute('id');
      const isMatch = scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight;

      results.push({
        id: sectionId,
        offsetTop: sectionTop,
        scrollPos: scrollPos,
        isMatch: isMatch,
        sectionTop: sectionTop,
        sectionBottom: sectionTop + sectionHeight
      });
    });

    return {
      windowScrollY: window.scrollY,
      detectionOffset: 120,
      sections: results
    };
  });
  console.log('Detection info:', JSON.stringify(detectionInfo, null, 2));

  await browser.close();
})();
