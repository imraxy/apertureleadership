const { chromium, devices } = require('playwright');

(async () => {
  const browser = await chromium.launch();
  const context = await browser.newContext({
    ...devices['iPhone 12 Pro'],
    viewport: { width: 390, height: 844 }
  });

  const page = await context.newPage();

  // Navigate to the page
  await page.goto('https://new.apertureleadership.com/posts?v=5');
  await page.waitForLoadState('networkidle');

  // Scroll down to trigger sticky nav first
  await page.evaluate(() => window.scrollTo(0, 500));
  await page.waitForTimeout(1000);

  // Get initial state
  const beforeClick = await page.evaluate(() => {
    const stickyNav = document.querySelector('.guidelines-sidebar.is-sticky');
    const activeLink = document.querySelector('.guidelines-nav a.active');
    return {
      hasStickyNav: !!stickyNav,
      stickyNavHeight: stickyNav ? stickyNav.offsetHeight : 0,
      activeLinkBefore: activeLink ? activeLink.textContent.trim() : 'none',
      scrollY: window.scrollY
    };
  });
  console.log('Before click:', JSON.stringify(beforeClick, null, 2));

  // Click on "For Individuals" nav link
  const navLink = await page.locator('a[href="#guideline-5"]').first();
  await navLink.click();

  // Wait for smooth scroll to complete (check if scroll position stabilizes)
  let lastScrollY = 0;
  let stableCount = 0;
  for (let i = 0; i < 50; i++) {
    await page.waitForTimeout(100);
    const currentScrollY = await page.evaluate(() => window.scrollY);
    if (Math.abs(currentScrollY - lastScrollY) < 2) {
      stableCount++;
      if (stableCount > 3) break;
    } else {
      stableCount = 0;
    }
    lastScrollY = currentScrollY;
  }

  // Get state after scroll completes
  const afterScroll = await page.evaluate(() => {
    const activeLink = document.querySelector('.guidelines-nav a.active');
    const guideline5 = document.getElementById('guideline-5');
    const stickyNav = document.querySelector('.guidelines-sidebar.is-sticky');
    const rect = guideline5 ? guideline5.getBoundingClientRect() : null;

    // Check which section is actually at the top of viewport
    let sectionAtTop = '';
    const sections = document.querySelectorAll('.guideline-card');
    sections.forEach(section => {
      const sectionRect = section.getBoundingClientRect();
      if (sectionRect.top >= 0 && sectionRect.top < 200) {
        sectionAtTop = section.id;
      }
    });

    return {
      scrollY: window.scrollY,
      activeLinkAfter: activeLink ? activeLink.textContent.trim() : 'none',
      guideline5OffsetTop: guideline5 ? guideline5.offsetTop : null,
      guideline5TopInViewport: rect ? rect.top : null,
      stickyNavHeight: stickyNav ? stickyNav.offsetHeight : 0,
      sectionAtTop: sectionAtTop
    };
  });
  console.log('After scroll completes:', JSON.stringify(afterScroll, null, 2));

  // Take screenshot
  await page.screenshot({ path: '/mnt/i/personal/apertureleadership/test_detailed.png', fullPage: false });
  console.log('Screenshot saved');

  await browser.close();
})();
