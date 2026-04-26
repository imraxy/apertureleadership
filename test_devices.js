const { chromium, devices } = require('playwright');

(async () => {
  // Test with different mobile devices
  const deviceNames = ['iPhone 12 Pro', 'iPhone SE', 'Pixel 5'];

  for (const deviceName of deviceNames) {
    console.log(`\n=== Testing ${deviceName} ===`);

    const browser = await chromium.launch();
    const context = await browser.newContext({
      ...devices[deviceName],
    });

    const page = await context.newPage();

    await page.goto('https://new.apertureleadership.com/posts?v=9');
    await page.waitForLoadState('networkidle');

    // Scroll down to trigger sticky nav
    await page.evaluate(() => window.scrollTo(0, 500));
    await page.waitForTimeout(1000);

    // Click on "For Individuals"
    const navLink = await page.locator('a[href="#guideline-5"]').first();
    await navLink.click();
    await page.waitForTimeout(1500);

    const result = await page.evaluate(() => {
      const stickyNav = document.querySelector('.guidelines-sidebar.is-sticky');
      const guideline5 = document.getElementById('guideline-5');

      return {
        viewportWidth: window.innerWidth,
        viewportHeight: window.innerHeight,
        scrollY: window.scrollY,
        stickyNavHeight: stickyNav ? stickyNav.offsetHeight : 0,
        stickyNavRect: stickyNav ? stickyNav.getBoundingClientRect() : null,
        guideline5Top: guideline5 ? guideline5.getBoundingClientRect().top : null,
      };
    });

    console.log('Result:', JSON.stringify(result, null, 2));

    // Calculate if there's overlap
    if (result.stickyNavRect && result.guideline5Top !== null) {
      const navBottom = result.stickyNavRect.bottom;
      const overlap = navBottom - result.guideline5Top;
      console.log(`Nav bottom: ${navBottom}, Section top: ${result.guideline5Top}`);
      console.log(`Overlap: ${overlap > 0 ? overlap + 'px (BAD)' : 'None (GOOD)'}`);
    }

    await browser.close();
  }
})();
