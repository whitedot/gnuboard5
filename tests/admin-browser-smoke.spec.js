const { test, expect } = require('@playwright/test');

const adminId = process.env.G5_ADMIN_ID || '';
const adminPassword = process.env.G5_ADMIN_PASSWORD || '';

function normalizeBaseUrl(baseURL) {
  return baseURL.endsWith('/') ? baseURL : `${baseURL}/`;
}

function getAppUrl(baseURL, pathname) {
  return new URL(pathname.replace(/^\/+/, ''), normalizeBaseUrl(baseURL)).toString();
}

async function loginAsAdmin(page, baseURL) {
  const returnUrl = getAppUrl(baseURL, 'adm/index.php');

  await page.goto(getAppUrl(baseURL, `member/login.php?url=${encodeURIComponent(returnUrl)}`), {
    waitUntil: 'domcontentloaded',
  });
  await page.locator('#login_id').fill(adminId);
  await page.locator('#login_pw').fill(adminPassword);
  await page.getByRole('button', { name: '로그인' }).click();
  await page.waitForLoadState('domcontentloaded');
}

test.describe('admin browser smoke', () => {
  test('unauthenticated admin export access redirects to login', async ({ page, baseURL }) => {
    await page.goto(getAppUrl(baseURL, 'adm/member_list_exel.php'), { waitUntil: 'domcontentloaded' });

    await expect(page).toHaveURL(/\/member\/login\.php/);
    await expect(page.getByRole('heading', { name: '회원로그인' })).toBeVisible();

    const urlValue = await page.locator('input[name="url"]').inputValue();
    expect(urlValue).toContain('/adm');
    expect(urlValue).toContain(new URL('adm/', normalizeBaseUrl(baseURL)).pathname);
  });

  test('admin can open member list and export pages', async ({ page, baseURL }) => {
    test.skip(!adminId || !adminPassword, 'G5_ADMIN_ID and G5_ADMIN_PASSWORD are required for authenticated admin smoke.');

    await loginAsAdmin(page, baseURL);

    await page.goto(getAppUrl(baseURL, 'adm/member_list.php'), { waitUntil: 'domcontentloaded' });
    await expect(page.locator('#adminNavList')).toBeVisible();
    await expect(page.getByRole('button', { name: '검색' })).toBeVisible();
    await expect(page.getByText('회원 삭제 안내')).toBeVisible();

    await page.goto(getAppUrl(baseURL, 'adm/member_list_exel.php'), { waitUntil: 'domcontentloaded' });
    await expect(page.getByRole('heading', { name: '회원 엑셀 생성' })).toBeVisible();
    await expect(page.getByRole('button', { name: '엑셀파일 다운로드' })).toBeVisible();
    await expect(page.locator('#fsearch')).toBeVisible();
  });
});
