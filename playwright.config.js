const { defineConfig } = require('@playwright/test');

const baseURL = process.env.G5_BASE_URL || 'http://127.0.0.1/g5';

module.exports = defineConfig({
  testDir: './tests',
  timeout: 30000,
  fullyParallel: false,
  retries: 0,
  reporter: 'list',
  use: {
    baseURL,
    headless: true,
    trace: 'retain-on-failure',
    screenshot: 'only-on-failure',
  },
});
