const puppeteer = require('puppeteer-core');

const CHROME_PATH = process.env.CHROME_PATH ||
  (process.platform === 'darwin' ? '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome' :
   process.platform === 'win32' ? 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe' :
   '/usr/bin/google-chrome');

(async () => {
  const browser = await puppeteer.launch({
    executablePath: CHROME_PATH,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });
  const page = await browser.newPage();
  
  page.on('console', msg => console.log('PAGE LOG:', msg.text()));
  page.on('pageerror', err => console.log('PAGE ERROR:', err.toString()));
  
  console.log("Navigating to login...");
  await page.goto('http://localhost:8000/login');
  
  console.log("Typing credentials...");
  await page.type('input[name=email]', 'dummyartist@test.com');
  await page.type('input[name=password]', 'password');
  
  console.log("Submitting login...");
  await Promise.all([
    page.click('button[type=submit]'),
    page.waitForNavigation({ waitUntil: 'networkidle0' }),
  ]);
  
  console.log("Current URL:", page.url());
  
  console.log("Going to artist dashboard...");
  await page.goto('http://localhost:8000/artist/home', { waitUntil: 'networkidle0' });
  
  console.log("Waiting for aplayer...");
  try {
    await page.waitForSelector('.aplayer-play', { timeout: 5000 });
    console.log("Aplayer found! Clicking play...");
    await page.click('.aplayer-play');
    await new Promise(r => setTimeout(r, 2000));
    console.log("Play clicked and waited 2s.");
  } catch(e) {
    console.log("Aplayer not found or click failed:", e.message);
  }

  await browser.close();
})();
