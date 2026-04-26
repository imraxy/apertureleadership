const { chromium, devices } = require('playwright');

(async () => {
  const browser = await chromium.launch();
  const context = await browser.newContext({
    ...devices['iPhone 12 Pro'],
    viewport: { width: 390, height: 844 }
  });

  const page = await context.newPage();

  // Navigate to the page with cache buster
  await page.goto('https://new.apertureleadership.com/posts?v=6');
  await page.waitForLoadState('networkidle');

  // Scroll down to trigger sticky nav first
  await page.evaluate(() => window.scrollTo(0, 500));
  await page.waitForTimeout(1000);

  // Click on "For Individuals" nav link
  const navLink = await page.locator('a[href="#guideline-5"]').first();
  await navLink.click();

  // Wait longer for everything to settle (smooth scroll + timeout)
  await page.waitForTimeout(2000);

  // Get state after everything settles
  const afterScroll = await page.evaluate(() => {
    const activeLink = document.querySelector('.guidelines-nav a.active');
    const guideline5 = document.getElementById('guideline-5');
    const rect = guideline5 ? guideline5.getBoundingClientRect() : null;

    // Get all nav links and their state
    const allLinks = Array.from(document.querySelectorAll('.guidelines-nav a')).map(link => ({
      text: link.textContent.trim(),
      href: link.getAttribute('href'),
      isActive: link.classList.contains('active')
    }));

    return {
      scrollY: window.scrollY,
      activeLinkText: activeLink ? activeLink.textContent.trim() : 'none',
      guideline5TopInViewport: rect ? rect.top : null,
      allLinks: allLinks
    };
  });
  console.log('After 2s wait:', JSON.stringify(afterScroll, null, 2));

  await browser.close();
})();
