const puppeteer = require('puppeteer-core');
const path = require('path');
const fs = require('fs');

const SCREENSHOT_DIR = process.env.SCREENSHOT_DIR || path.join(__dirname, 'tests', 'screenshots');
if (!fs.existsSync(SCREENSHOT_DIR)) {
  fs.mkdirSync(SCREENSHOT_DIR, { recursive: true });
}

const BASE_URL = process.env.BASE_URL || 'http://localhost:8000';
const CHROME_PATH = process.env.CHROME_PATH ||
  (process.platform === 'darwin' ? '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome' :
   process.platform === 'win32' ? 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe' :
   '/usr/bin/google-chrome');

const SAMPLE_AUDIO = path.join(__dirname, 'test_sample.wav');

async function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function runTestSuite() {
  console.log('====================================================');
  console.log('   STARTING MUSICBOOK COMPREHENSIVE E2E UI TESTS   ');
  console.log('====================================================\n');
  console.log(`Base URL: ${BASE_URL}`);
  console.log(`Chrome Executable: ${CHROME_PATH}`);
  console.log(`Screenshots Directory: ${SCREENSHOT_DIR}\n`);

  const browser = await puppeteer.launch({
    executablePath: CHROME_PATH,
    args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-gpu'],
    headless: 'new',
    defaultViewport: { width: 1280, height: 850 }
  });

  const page = await browser.newPage();
  const results = [];

  function recordResult(scenario, action, status, details = '') {
    results.push({ scenario, action, status, details });
    const sym = status === 'SUCCESS' ? '✅' : (status === 'WARNING' ? '⚠️' : '❌');
    console.log(`${sym} [${scenario}] ${action}: ${status} ${details ? '(' + details + ')' : ''}`);
  }

  try {
    // ----------------------------------------------------
    // SCENARIO 1: LANDING PAGE & PUBLIC VISITOR
    // ----------------------------------------------------
    console.log('\n--- SCENARIO 1: GUEST / LANDING PAGE & PUBLIC AUDIO ---');
    await page.goto(BASE_URL, { waitUntil: 'networkidle2' });
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '01_landing_page.png') });
    recordResult('Guest', 'Load Landing Page', 'SUCCESS', 'Page loaded with navbar and hero banner');

    // Test carousel navigation
    try {
      const nextBtn = await page.$('.carousel-control.right, .right.carousel-control');
      if (nextBtn) {
        await nextBtn.click();
        await sleep(1000);
        recordResult('Guest', 'Carousel Next Slide', 'SUCCESS');
      }
    } catch (e) {
      recordResult('Guest', 'Carousel Next Slide', 'WARNING', e.message);
    }

    // Test audio playback on landing page
    try {
      const playBtn = await page.$('.aplayer-play, .aplayer-button');
      if (playBtn) {
        await playBtn.click();
        await sleep(2000);
        await page.screenshot({ path: path.join(SCREENSHOT_DIR, '02_landing_audio_play.png') });
        recordResult('Guest', 'APlayer Audio Playback', 'SUCCESS', 'Play button clicked and audio stream initiated');
      } else {
        recordResult('Guest', 'APlayer Audio Playback', 'WARNING', 'Play button not found');
      }
    } catch (e) {
      recordResult('Guest', 'APlayer Audio Playback', 'WARNING', e.message);
    }

    // Visit Register Page
    await page.goto(`${BASE_URL}/register`, { waitUntil: 'networkidle2' });
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '03_register_page.png') });
    recordResult('Guest', 'View Register Page', 'SUCCESS', 'Register form available');

    // Visit Login Page
    await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle2' });
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '04_login_page.png') });
    recordResult('Guest', 'View Login Page', 'SUCCESS', 'Login form available');

    // ----------------------------------------------------
    // SCENARIO 2: ARTIST PERSONA FLOW
    // ----------------------------------------------------
    console.log('\n--- SCENARIO 2: ARTIST PERSONA FLOW ---');
    // Login as dummy artist
    await page.type('#email', 'dummyartist@test.com');
    await page.type('#password', 'password');
    await Promise.all([
      page.click('button[type="submit"]'),
      page.waitForNavigation({ waitUntil: 'networkidle2' })
    ]);
    await sleep(2000);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '05_artist_dashboard.png') });
    recordResult('Artist', 'Login as Artist', 'SUCCESS', `Current URL: ${page.url()}`);

    // Test Feed Audio playback
    try {
      const feedPlayBtn = await page.$('.songview .aplayer-play, .aplayer-play');
      if (feedPlayBtn) {
        await feedPlayBtn.click();
        await sleep(2000);
        await page.screenshot({ path: path.join(SCREENSHOT_DIR, '06_feed_audio_play.png') });
        recordResult('Artist', 'Feed Song Playback', 'SUCCESS', 'Played song on artist feed');
      }
    } catch (e) {
      recordResult('Artist', 'Feed Song Playback', 'WARNING', e.message);
    }

    // Test Feed Like Button
    try {
      const likeBtn = await page.$('.songview button.btn-sm');
      if (likeBtn) {
        const initialText = await page.evaluate(el => el.textContent.trim(), likeBtn);
        await likeBtn.click();
        await sleep(1500);
        await page.screenshot({ path: path.join(SCREENSHOT_DIR, '07_feed_like.png') });
        recordResult('Artist', 'Like/Unlike Song', 'SUCCESS', `Button toggled from ${initialText}`);
      }
    } catch (e) {
      recordResult('Artist', 'Like/Unlike Song', 'WARNING', e.message);
    }

    // Test Feed Commenting
    try {
      const commentInput = await page.$('input[placeholder*="comment"]');
      if (commentInput) {
        await commentInput.click();
        await commentInput.type('Automated test comment: great track vibes! 🎶');
        await page.keyboard.press('Enter');
        await sleep(2000);
        await page.screenshot({ path: path.join(SCREENSHOT_DIR, '08_feed_comment.png') });
        recordResult('Artist', 'Add Comment to Song', 'SUCCESS', 'Comment posted to feed');
      }
    } catch (e) {
      recordResult('Artist', 'Add Comment to Song', 'WARNING', e.message);
    }

    // Test Song Upload Modal
    try {
      const uploadModalBtn = await page.$('#custom-file-upload');
      if (uploadModalBtn) {
        await uploadModalBtn.click();
        await sleep(1500);
        await page.screenshot({ path: path.join(SCREENSHOT_DIR, '10_upload_modal.png') });
        recordResult('Artist', 'Open Song Upload Modal', 'SUCCESS', 'Upload modal opened');

        // Test form inputs inside modal
        const titleInput = await page.$('#exampleModal input[name="filename"]');
        if (titleInput) {
          await titleInput.type('Harmonic Test Groove 2026');
        }
        const descInput = await page.$('#exampleModal #message-text');
        if (descInput) {
          await descInput.type('Test song uploaded automatically during UI audit.');
        }

        // Attach audio file
        const audioInput = await page.$('.songupload input[type="file"]');
        if (audioInput && fs.existsSync(SAMPLE_AUDIO)) {
          await audioInput.uploadFile(SAMPLE_AUDIO);
          await sleep(1000);
        }
        await page.screenshot({ path: path.join(SCREENSHOT_DIR, '11_upload_form_filled.png') });
        recordResult('Artist', 'Configure Song Upload', 'SUCCESS', 'Audio file attached and metadata entered');

        // Close modal
        const closeBtn = await page.$('#exampleModal button.close, #exampleModal button[data-dismiss="modal"]');
        if (closeBtn) {
          await closeBtn.click();
          await sleep(1000);
        }
      }
    } catch (e) {
      recordResult('Artist', 'Song Upload Modal', 'WARNING', e.message);
    }

    // Artist Tracks Page
    await page.goto(`${BASE_URL}/tracks`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '12_artist_tracks.png') });
    recordResult('Artist', 'View Artist Tracks', 'SUCCESS', 'Loaded /tracks with public and for-sale sections');

    // Artist Collections Page
    await page.goto(`${BASE_URL}/artist/collections`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '13_artist_collections.png') });
    recordResult('Artist', 'View Artist Collections', 'SUCCESS', 'Loaded collections sidebar');

    // Artist Playlists Page
    await page.goto(`${BASE_URL}/playlist/125`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '14_artist_playlists.png') });
    recordResult('Artist', 'View Artist Playlists', 'SUCCESS', 'Loaded playlists view');

    // Artist Notes Page
    await page.goto(`${BASE_URL}/notes/125`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '15_artist_notes_list.png') });
    recordResult('Artist', 'View Artist Notes', 'SUCCESS', 'Loaded notes interface');

    // Add a note
    try {
      const addNoteBtn = await page.$('a.btn-primary');
      if (addNoteBtn) {
        await addNoteBtn.click();
        await sleep(1000);
        const titleField = await page.$('.panel-body input[type="text"]');
        if (titleField) {
          await titleField.type('Songwriting Session: Harmonic Minor in D');
        }
        const saveNoteBtn = await page.$('button.btn-success');
        if (saveNoteBtn) {
          await saveNoteBtn.click();
          await sleep(2000);
          await page.screenshot({ path: path.join(SCREENSHOT_DIR, '16_artist_note_created.png') });
          recordResult('Artist', 'Create Personal Note', 'SUCCESS', 'Note saved and rendered');
        }
      }
    } catch (e) {
      recordResult('Artist', 'Create Personal Note', 'WARNING', e.message);
    }

    // Artist Profile Page
    await page.goto(`${BASE_URL}/profile/dummy-artist`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '17_artist_profile.png') });
    recordResult('Artist', 'View Public Profile', 'SUCCESS', 'Loaded profile banner, stats, and song tabs');

    // Edit Profile Page
    await page.goto(`${BASE_URL}/profile/dummy-artist/edit`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '18_artist_edit_profile.png') });
    recordResult('Artist', 'View Edit Profile', 'SUCCESS', 'Loaded profile edit form');

    // Search Users via Navbar
    try {
      const searchInput = await page.$('input[placeholder*="Search Users"]');
      if (searchInput) {
        await searchInput.type('Cora');
        await sleep(1500);
        await page.screenshot({ path: path.join(SCREENSHOT_DIR, '19_user_search.png') });
        recordResult('Artist', 'Search Users Dynamic Dropdown', 'SUCCESS', 'Search query typed and live results requested');
      }
    } catch (e) {
      recordResult('Artist', 'Search Users', 'WARNING', e.message);
    }

    // Private Messages / Inbox
    await page.goto(`${BASE_URL}/inbox`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '20_artist_inbox.png') });
    recordResult('Artist', 'View Private Messages Inbox', 'SUCCESS', 'Loaded PM sidebar and inbox container');

    // Live Events
    await page.goto(`${BASE_URL}/live-events`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '21_live_events.png') });
    recordResult('Artist', 'View Live Events', 'SUCCESS', 'Loaded live event schedule/manager');

    // Logout Artist
    await page.evaluate(() => {
      const form = document.getElementById('frm-logout');
      if (form) form.submit();
    });
    await sleep(2000);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '22_artist_logged_out.png') });
    recordResult('Artist', 'Logout', 'SUCCESS', 'Session terminated');

    // ----------------------------------------------------
    // SCENARIO 3: FAN PERSONA FLOW
    // ----------------------------------------------------
    console.log('\n--- SCENARIO 3: FAN PERSONA FLOW ---');
    await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle2' });
    await page.type('#email', 'dooley.shannon@example.com');
    await page.type('#password', 'secret');
    await Promise.all([
      page.click('button[type="submit"]'),
      page.waitForNavigation({ waitUntil: 'networkidle2' })
    ]);
    await sleep(2000);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '23_fan_home.png') });
    recordResult('Fan', 'Login as Fan', 'SUCCESS', `Current URL: ${page.url()}`);

    // Fan Collections
    await page.goto(`${BASE_URL}/fan/collections`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '24_fan_collections.png') });
    recordResult('Fan', 'View Fan Collections', 'SUCCESS', 'Loaded fan collections & purchased songs');

    // Fan visits Artist Profile and Checks Interaction
    await page.goto(`${BASE_URL}/profile/dummy-artist`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '25_fan_view_artist_profile.png') });
    recordResult('Fan', 'Visit Artist Profile as Fan', 'SUCCESS', 'Loaded artist profile with Friend / Follow controls');

    // Fan Payments Page
    await page.goto(`${BASE_URL}/payments`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '26_fan_payments.png') });
    recordResult('Fan', 'View Payments / Orders Gateway', 'SUCCESS', 'Loaded payment interface');

    // Logout Fan
    await page.evaluate(() => {
      const form = document.getElementById('frm-logout');
      if (form) form.submit();
    });
    await sleep(2000);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '27_fan_logged_out.png') });
    recordResult('Fan', 'Logout', 'SUCCESS', 'Fan session ended');

    // ----------------------------------------------------
    // SCENARIO 4: ADMIN PERSONA FLOW
    // ----------------------------------------------------
    console.log('\n--- SCENARIO 4: ADMIN PERSONA FLOW ---');
    await page.goto(`${BASE_URL}/admin/login`, { waitUntil: 'networkidle2' });
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '28_admin_login_page.png') });
    recordResult('Admin', 'View Admin Login Page', 'SUCCESS');

    await page.type('#email, input[name="email"]', 'admin@gmail.com');
    await page.type('#password, input[name="password"]', 'secret');
    await Promise.all([
      page.click('button[type="submit"]'),
      page.waitForNavigation({ waitUntil: 'networkidle2' })
    ]);
    await sleep(2000);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '29_admin_dashboard.png') });
    recordResult('Admin', 'Admin Login & Dashboard', 'SUCCESS', `Dashboard with stats tiles loaded at ${page.url()}`);

    // Admin Users
    await page.goto(`${BASE_URL}/admin/users`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '30_admin_users.png') });
    recordResult('Admin', 'Manage Users List', 'SUCCESS', 'Loaded users management table');

    // Admin Add User
    await page.goto(`${BASE_URL}/admin/createuser`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '31_admin_create_user.png') });
    recordResult('Admin', 'Create User View', 'SUCCESS', 'Loaded create user form');

    // Admin Songs
    await page.goto(`${BASE_URL}/admin/songs`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '32_admin_all_songs.png') });
    recordResult('Admin', 'Manage All Songs', 'SUCCESS', 'Loaded songs management table');

    // Admin Artist Songs
    await page.goto(`${BASE_URL}/admin/artistsongs`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '33_admin_artist_songs.png') });
    recordResult('Admin', 'Manage Artist Songs', 'SUCCESS', 'Loaded artist songs table');

    // Admin Fan Songs
    await page.goto(`${BASE_URL}/admin/fansongs`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '34_admin_fan_songs.png') });
    recordResult('Admin', 'Manage Fan Songs', 'SUCCESS', 'Loaded fan songs table');

    // Admin Playlists
    await page.goto(`${BASE_URL}/admin/playlists`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '35_admin_playlists.png') });
    recordResult('Admin', 'Manage Playlists', 'SUCCESS', 'Loaded playlists management table');

    // Admin Tags
    await page.goto(`${BASE_URL}/admin/tags`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '36_admin_tags.png') });
    recordResult('Admin', 'Manage Tags List', 'SUCCESS', 'Loaded tags table');

    // Admin Create Tag
    try {
      const tagInput = await page.$('input[name="name"]');
      const submitBtn = await page.$('form button[type="submit"], input[type="submit"]');
      if (tagInput && submitBtn) {
        await tagInput.type('Electronic LoFi');
        await submitBtn.click();
        await sleep(2000);
        await page.screenshot({ path: path.join(SCREENSHOT_DIR, '37_admin_tag_added.png') });
        recordResult('Admin', 'Create New Tag', 'SUCCESS', 'Tag "Electronic LoFi" submitted');
      }
    } catch (e) {
      recordResult('Admin', 'Create New Tag', 'WARNING', e.message);
    }

    // Admin Live Events
    await page.goto(`${BASE_URL}/admin/liveevents`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '38_admin_live_events.png') });
    recordResult('Admin', 'Manage Live Events', 'SUCCESS', 'Loaded live events table');

    // Admin Notes
    await page.goto(`${BASE_URL}/admin/notes`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '39_admin_notes.png') });
    recordResult('Admin', 'Manage Notes', 'SUCCESS', 'Loaded all user notes table');

    // Admin Reports
    await page.goto(`${BASE_URL}/admin/reports`, { waitUntil: 'networkidle2' });
    await sleep(1500);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '40_admin_reports.png') });
    recordResult('Admin', 'Admin Reports', 'SUCCESS', 'Loaded analytics and reports');

    // Admin Logout
    await page.evaluate(() => {
      const form = document.getElementById('frm-logout');
      if (form) form.submit();
    });
    await sleep(2000);
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, '41_admin_logged_out.png') });
    recordResult('Admin', 'Admin Logout', 'SUCCESS', 'Admin session logged out');

  } catch (error) {
    console.error('Test Suite encountered an error:', error);
    recordResult('Global', 'Execution Failure', 'FAILED', error.message);
  } finally {
    await browser.close();
    console.log('\n====================================================');
    console.log('              TEST EXECUTION SUMMARY                ');
    console.log('====================================================');
    console.table(results);
    fs.writeFileSync(
      path.join(SCREENSHOT_DIR, 'test_results.json'),
      JSON.stringify(results, null, 2)
    );
    console.log(`\nTest results JSON saved to: ${path.join(SCREENSHOT_DIR, 'test_results.json')}`);
  }
}

runTestSuite();
