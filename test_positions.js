const { chromium, devices } = require('playwright');

(async () => {
  const browser = await chromium.launch();
  const context = await browser.newContext({
    ...devices['iPhone 12 Pro'],
    viewport: { width: 390, height: 844 }
  });

  const page = await context.newPage();

  // Navigate to the page
  await page.goto('https://new.apertureleadership.com/posts?v=8');
  await page.waitForLoadState('networkidle');

  // Scroll down to trigger sticky nav
  await page.evaluate(() => window.scrollTo(0, 500));
  await page.waitForTimeout(1000);

  console.log('=== FIRST CLICK (For Individuals) ===');

  // Click on "For Individuals" nav link
  const navLink = await page.locator('a[href="#guideline-5"]').first();
  await navLink.click();
  await page.waitForTimeout(1500);

  // Get detailed position info
  const firstClick = await page.evaluate(() => {
    const stickyNav = document.querySelector('.guidelines-sidebar.is-sticky');
    const guideline5 = document.getElementById('guideline-5');
    const title = guideline5 ? guideline5.querySelector('h3') : null;

    return {
      scrollY: window.scrollY,
      stickyNavHeight: stickyNav ? stickyNav.offsetHeight : 0,
      stickyNavRect: stickyNav ? stickyNav.getBoundingClientRect() : null,
      guideline5Rect: guideline5 ? guideline5.getBoundingClientRect() : null,
      titleRect: title ? title.getBoundingClientRect() : null,
      titleText: title ? title.textContent.trim() : null
    };
  });
  console.log('First click result:', JSON.stringify(firstClick, null, 2));

  await page.screenshot({ path: '/mnt/i/personal/apertureleadership/test_first_click.png', fullPage: false });

  console.log('\n=== SECOND CLICK (For Teams) ===');

  // Click on "For Teams" nav link
  const navLink2 = await page.locator('a[href="#guideline-6"]').first();
  await navLink2.click();
  await page.waitForTimeout(1500);

  const secondClick = await page.evaluate(() => {
    const stickyNav = document.querySelector('.guidelines-sidebar.is-sticky');
    const guideline6 = document.getElementById('guideline-6');
    const title = guideline6 ? guideline6.querySelector('h3') : null;

    return {
      scrollY: window.scrollY,
      stickyNavHeight: stickyNav ? stickyNav.offsetHeight : 0,
      stickyNavRect: stickyNav ? stickyNav.getBoundingClientRect() : null,
      guideline6Rect: guideline6 ? guideline6.getBoundingClientRect() : null,
      titleRect: title ? title.getBoundingClientRect() : null,
      titleText: title ? title.textContent.trim() : null
    };
  });
  console.log('Second click result:', JSON.stringify(secondClick, null, 2));

  await page.screenshot({ path: '/mnt/i/personal/apertureleadership/test_second_click.png', fullPage: false });

  await browser.close();
})();
