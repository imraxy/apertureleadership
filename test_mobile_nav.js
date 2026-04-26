const { chromium, devices } = require('playwright');

(async () => {
  // Use iPhone 12 Pro for mobile simulation
  const browser = await chromium.launch();
  const context = await browser.newContext({
    ...devices['iPhone 12 Pro'],
    viewport: { width: 390, height: 844 }
  });

  const page = await context.newPage();

  // Navigate to the page
  await page.goto('https://new.apertureleadership.com/posts?v=4');
  await page.waitForLoadState('networkidle');

  // Take initial screenshot
  await page.screenshot({ path: '/mnt/i/personal/apertureleadership/test_mobile_1_initial.png', fullPage: false });
  console.log('Screenshot 1: Initial page load');

  // Scroll down to trigger sticky nav
  await page.evaluate(() => window.scrollTo(0, 500));
  await page.waitForTimeout(500);
  await page.screenshot({ path: '/mnt/i/personal/apertureleadership/test_mobile_2_scrolled.png', fullPage: false });
  console.log('Screenshot 2: After scrolling (sticky nav should appear)');

  // Click on "For Individuals" nav link (guideline-5)
  const navLink = await page.locator('a[href="#guideline-5"]').first();
  await navLink.click();
  await page.waitForTimeout(1000); // Wait for smooth scroll
  await page.screenshot({ path: '/mnt/i/personal/apertureleadership/test_mobile_3_after_click.png', fullPage: false });
  console.log('Screenshot 3: After clicking "For Individuals"');

  // Get scroll position and active nav state
  const state = await page.evaluate(() => {
    const activeLink = document.querySelector('.guidelines-nav a.active');
    const stickyNav = document.querySelector('.guidelines-sidebar.is-sticky');
    const guideline5 = document.getElementById('guideline-5');
    return {
      scrollY: window.scrollY,
      activeLinkText: activeLink ? activeLink.textContent.trim() : 'none',
      hasStickyNav: !!stickyNav,
      guideline5OffsetTop: guideline5 ? guideline5.offsetTop : null,
      guideline5Rect: guideline5 ? guideline5.getBoundingClientRect() : null
    };
  });
  console.log('State after click:', JSON.stringify(state, null, 2));

  // Click on another link - "For Teams"
  const navLink2 = await page.locator('a[href="#guideline-6"]').first();
  await navLink2.click();
  await page.waitForTimeout(1000);
  await page.screenshot({ path: '/mnt/i/personal/apertureleadership/test_mobile_4_second_click.png', fullPage: false });
  console.log('Screenshot 4: After clicking "For Teams"');

  const state2 = await page.evaluate(() => {
    const activeLink = document.querySelector('.guidelines-nav a.active');
    const guideline6 = document.getElementById('guideline-6');
    return {
      scrollY: window.scrollY,
      activeLinkText: activeLink ? activeLink.textContent.trim() : 'none',
      guideline6OffsetTop: guideline6 ? guideline6.offsetTop : null,
      guideline6Rect: guideline6 ? guideline6.getBoundingClientRect() : null
    };
  });
  console.log('State after second click:', JSON.stringify(state2, null, 2));

  await browser.close();
  console.log('Test complete');
})();
