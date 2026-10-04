const { chromium } = require('playwright');
const assert = require('assert');
/**
 * End-to-end checks for the Signal & Shield site.
 *
 *   npm install playwright
 *   node tests/e2e.js [screenshot-dir]
 *
 * Runs against SITE_URL (default http://localhost:8080). Set CHROMIUM_PATH to
 * use an existing Chromium. Each run submits one enquiry and one checklist
 * request; the site allows five of each per visitor every ten minutes.
 */
const os = require('os');
const BASE = process.env.SITE_URL || 'http://localhost:8080';
const SHOTS = process.argv[2] || os.tmpdir();
let failures = 0;
async function step(name, fn) {
  try { await fn(); console.log('PASS', name); }
  catch (e) { failures++; console.log('FAIL', name, '\n   ', e.message.split('\n')[0]); }
}

(async () => {
  const browser = await chromium.launch(process.env.CHROMIUM_PATH ? { executablePath: process.env.CHROMIUM_PATH } : {});
  const ctx = await browser.newContext({ viewport: { width: 1280, height: 900 } });
  const page = await ctx.newPage();
  const errors = [];
  page.on('pageerror', e => errors.push(e.message));
  page.on('console', m => { if (m.type() === 'error') errors.push(m.text()); });

  // ---------- Health check ----------
  await page.goto(BASE + '/network-health-check/');
  await step('quiz shows one question with progress', async () => {
    assert.equal(await page.locator('[data-ss-question]:visible').count(), 1);
    assert.equal((await page.textContent('[data-ss-count]')).trim(), 'Question 1 of 10');
    assert.equal(await page.getAttribute('[data-ss-progress]', 'aria-valuenow'), '1');
    assert.ok(await page.locator('[data-ss-back]').isHidden());
  });
  await step('quiz blocks Next without an answer', async () => {
    await page.click('[data-ss-next]');
    assert.ok(await page.locator('#ss-q-coverage [data-ss-question-error]').isVisible());
    assert.equal(await page.evaluate(() => document.activeElement.id), 'ss-q-coverage-0');
  });
  // Answers: wireless all best (2,2,2) -> green; network 1,1,0 -> 2/6 = red? 0.33 -> red; security mixed 2,1,1,0 -> 4/8 -> amber
  const picks = { coverage: 0, busy: 0, survey: 0, guest: 1, resilience: 1, documentation: 3, firewall: 0, backups: 1, passwords: 1, updates: 2 };
  await step('quiz keyboard: answer with arrow keys and Enter', async () => {
    await page.keyboard.press('Space'); // selects focused first option
    assert.ok(await page.isChecked('#ss-q-coverage-0'));
    assert.ok(await page.locator('#ss-q-coverage [data-ss-question-error]').isHidden());
    await page.keyboard.press('Enter');
    assert.equal((await page.textContent('[data-ss-count]')).trim(), 'Question 2 of 10');
    assert.equal(await page.evaluate(() => document.activeElement.hasAttribute('data-ss-question-text')), true);
  });
  await step('quiz back button returns with answer kept', async () => {
    await page.click('[data-ss-back]');
    assert.equal((await page.textContent('[data-ss-count]')).trim(), 'Question 1 of 10');
    assert.ok(await page.isChecked('#ss-q-coverage-0'));
    await page.click('[data-ss-next]');
  });
  await step('quiz completes and shows scorecard', async () => {
    const ids = Object.keys(picks).slice(1);
    for (const id of ids) {
      await page.click(`label[for="ss-q-${id}-${picks[id]}"]`);
      if (id === 'resilience') await page.screenshot({ path: SHOTS + '/quiz-question.png' });
      await page.click('[data-ss-next]');
    }
    await page.waitForSelector('[data-ss-scorecard]:visible');
    assert.equal(await page.evaluate(() => document.activeElement.className), 'ss-scorecard__title');
    const ratings = await page.$$eval('[data-ss-area]', els => els.map(e => e.getAttribute('data-ss-area') + ':' + e.className.split('--')[1]));
    assert.deepEqual(ratings, ['wireless:green', 'network:red', 'security:amber']);
    const visibleRatingText = await page.$$eval('[data-ss-rating] > span:not([hidden])', els => els.map(e => e.textContent.trim()));
    assert.deepEqual(visibleRatingText, ['Rating: Green', 'Rating: Red', 'Rating: Amber']);
    const tips = await page.$$eval('[data-ss-tip]', els => els.map(e => e.textContent));
    assert.ok(tips[0].startsWith('Your Wi-Fi sounds healthy'), tips[0]);
    assert.ok(tips[1].startsWith('Ask whoever manages your network'), tips[1]); // documentation = 0 points, weakest
    assert.ok(tips[2].startsWith('Switch on automatic updates'), tips[2]);
    assert.ok((await page.textContent('[data-ss-summary]')).startsWith('Some areas need attention soon'));
    const href = await page.getAttribute('[data-ss-book]', 'href');
    assert.ok(href.includes('result=wireless%3Agreen%2Cnetwork%3Ared%2Csecurity%3Aamber'), href);
    await page.screenshot({ path: SHOTS + '/quiz-scorecard.png', fullPage: false });
    await page.locator('.ss-scorecard').screenshot({ path: SHOTS + '/quiz-scorecard-card.png' });
  });
  await step('book link pre-fills contact form', async () => {
    await page.click('[data-ss-book]');
    await page.waitForLoadState('networkidle');
    assert.equal(await page.inputValue('#ss-contact-service'), 'health-check');
    const msg = await page.inputValue('#ss-contact-message');
    assert.ok(msg.includes('Wireless: Green, Network: Red, Security: Amber'), msg);
  });
  await step('retake resets the quiz', async () => {
    await page.goBack();
    await page.waitForSelector('[data-ss-scorecard]', { state: 'attached' });
    if (await page.locator('[data-ss-scorecard]').isHidden()) {
      // bfcache may restore; otherwise re-run quickly
      for (const id of Object.keys(picks)) { await page.click(`label[for="ss-q-${id}-${picks[id]}"]`); await page.click('[data-ss-next]'); }
    }
    await page.click('[data-ss-retake]');
    assert.equal((await page.textContent('[data-ss-count]')).trim(), 'Question 1 of 10');
    assert.equal(await page.locator('input[type=radio]:checked').count(), 0);
  });

  // ---------- Contact ----------
  await page.goto(BASE + '/contact/');
  await page.waitForTimeout(2200);
  await step('contact: empty submit shows summary and inline errors', async () => {
    await page.click('.ss-form button[type=submit]');
    const summary = page.locator('.ss-form__summary');
    assert.ok(await summary.isVisible());
    assert.equal(await page.evaluate(() => document.activeElement.classList.contains('ss-form__summary')), true);
    const items = await summary.locator('li').allTextContents();
    assert.deepEqual(items, ['Enter your name.', 'Enter your email address.', 'Choose the service you are interested in.', 'Tell us a little about what you need.']);
    assert.equal(await page.getAttribute('#ss-contact-name', 'aria-invalid'), 'true');
    assert.ok((await page.getAttribute('#ss-contact-name', 'aria-describedby')).includes('ss-contact-name-error'));
    await page.screenshot({ path: SHOTS + '/contact-errors.png', fullPage: false });
  });
  await step('contact: summary link focuses the field', async () => {
    await page.click('.ss-form__summary li:nth-child(2) a');
    assert.equal(await page.evaluate(() => document.activeElement.id), 'ss-contact-email');
  });
  await step('contact: invalid email and phone messages', async () => {
    await page.fill('#ss-contact-name', 'Aisha Rahman');
    await page.fill('#ss-contact-email', 'aisha@company');
    await page.fill('#ss-contact-phone', '12ab');
    await page.selectOption('#ss-contact-service', 'wireless');
    await page.fill('#ss-contact-message', 'Short');
    await page.click('.ss-form button[type=submit]');
    const items = await page.locator('.ss-form__summary li').allTextContents();
    assert.equal(items.length, 3, items.join(' | '));
    assert.ok(items[0].startsWith('Enter an email address in the format'));
    assert.ok(items[1].startsWith('Enter a phone number'));
    assert.ok(items[2].startsWith('Your message should be at least 10'));
  });
  await step('contact: error clears as user fixes field', async () => {
    await page.fill('#ss-contact-email', 'aisha@hotel-example.qa');
    assert.equal(await page.getAttribute('#ss-contact-email', 'aria-invalid'), null);
  });
  await step('contact: valid submission shows confirmation', async () => {
    await page.fill('#ss-contact-company', 'Pearl Bay Hotel');
    await page.fill('#ss-contact-phone', '+974 5555 1234');
    await page.fill('#ss-contact-message', 'Our pool deck and ballroom Wi-Fi is weak during events. Can you survey it next month?');
    await page.click('.ss-form button[type=submit]');
    await page.waitForSelector('.ss-form-success:visible', { timeout: 10000 });
    assert.ok((await page.textContent('.ss-form-success__text')).includes('Thank you, Aisha.'));
    assert.ok(await page.locator('form.ss-form').isHidden());
    assert.equal(await page.evaluate(() => document.activeElement.classList.contains('ss-form-success')), true);
    await page.locator('.ss-contact').screenshot({ path: SHOTS + '/contact-success.png' });
  });

  // ---------- Checklist ----------
  await page.goto(BASE + '/');
  await page.waitForTimeout(2200);
  await step('checklist: invalid email error', async () => {
    await page.fill('.ss-form--checklist input[name=email]', 'not-an-email');
    await page.click('.ss-form--checklist button[type=submit]');
    const err = page.locator('.ss-form--checklist .ss-field__error');
    assert.ok(await err.isVisible());
    assert.ok((await err.textContent()).startsWith('Enter an email address in the format'));
  });
  let downloadUrl;
  await step('checklist: valid email reveals download', async () => {
    await page.fill('.ss-form--checklist input[name=email]', 'it.manager@school-example.qa');
    await page.check('.ss-form--checklist input[name=tips]');
    await page.click('.ss-form--checklist button[type=submit]');
    await page.waitForSelector('.ss-form-success--checklist:visible', { timeout: 10000 });
    downloadUrl = await page.getAttribute('.ss-download-link', 'href');
    assert.ok(/\?ss_download=[A-Za-z0-9]{32}$/.test(downloadUrl), downloadUrl);
    await page.locator('#checklist').screenshot({ path: SHOTS + '/checklist-success.png' });
  });
  await step('checklist: download link serves the PDF', async () => {
    const res = await page.request.get(downloadUrl);
    assert.equal(res.status(), 200);
    assert.equal(res.headers()['content-type'], 'application/pdf');
    assert.ok((await res.body()).slice(0, 5).toString() === '%PDF-');
  });
  await step('checklist: bad token is refused', async () => {
    const res = await page.request.get(BASE + '/?ss_download=nottherighttoken');
    assert.equal(res.status(), 410);
  });
  await step('checklist: PDF folder not directly reachable', async () => {
    const res = await page.request.get(BASE + '/wp-content/plugins/signal-shield-core/downloads/office-wifi-checklist.pdf');
    assert.equal(res.status(), 403);
  });

  // ---------- Navigation ----------
  await step('nav marks current page', async () => {
    await page.goto(BASE + '/about/');
    assert.equal(await page.getAttribute('.ss-nav a[aria-current=page]', 'href'), BASE + '/about/');
  });
  await step('skip link targets main', async () => {
    await page.goto(BASE + '/');
    await page.keyboard.press('Tab');
    const t = await page.evaluate(() => [document.activeElement.className, document.activeElement.getAttribute('href')]);
    assert.ok(t[0].includes('skip-link'), t.join(','));
  });
  for (const w of [390, 800]) {
    await step(`mobile menu at ${w}px opens, traps and closes with Escape`, async () => {
      const m = await browser.newPage({ viewport: { width: w, height: 800 } });
      await m.goto(BASE + '/services/');
      const open = m.locator('.wp-block-navigation__responsive-container-open');
      assert.ok(await open.isVisible());
      assert.ok(await m.locator('.ss-nav .wp-block-navigation__container').isHidden());
      await open.click();
      await m.waitForSelector('.wp-block-navigation__responsive-container.is-menu-open');
      await m.waitForTimeout(300);
      await m.screenshot({ path: `${SHOTS}/menu-open-${w}.png` });
      assert.ok(await m.locator('.is-menu-open a:has-text("Case studies")').isVisible());
      const box = await m.locator('.wp-block-navigation__responsive-container.is-menu-open').boundingBox();
      assert.ok(box.height >= 790, 'overlay height ' + box.height);
      await m.keyboard.press('Escape');
      await m.waitForTimeout(300);
      assert.equal(await m.locator('.is-menu-open').count(), 0);
      await m.close();
    });
  }
  await step('desktop nav visible at 1280', async () => {
    await page.goto(BASE + '/');
    assert.ok(await page.locator('.ss-nav .wp-block-navigation__container').isVisible());
    assert.ok(await page.locator('.wp-block-navigation__responsive-container-open').isHidden());
  });

  console.log(errors.length ? 'JS errors: ' + errors.join(' | ') : 'No JS errors');
  console.log(failures ? `${failures} FAILED` : 'ALL PASSED');
  await browser.close();
})();
