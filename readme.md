# MusicBook 🎵

MusicBook is a full-stack social network, music streaming platform, and digital marketplace for musicians and listeners. Originally created as an 8th-semester engineering major project, the platform features audio playback with byte-range streaming, community interactions (likes, comments, shares, friendship, following), digital track sales via PayPal, YouTube live streaming integration, personal songwriting notes, real-time messaging, and a comprehensive administration back-office.

---

## 🌟 Key Features

- **Audio Streaming & Uploads**: Single and bulk album uploads (MP3/WAV), private and public visibility, custom cover art, and inline playback using [Vue-APlayer](https://vue-aplayer.js.org/) supporting HTTP 206 partial content streaming.
- **Social Networking**: User profiles for **Artists**, **Fans/Viewers**, and **Admins**. Follow artists, send friend requests, like, comment, and share songs on dynamic activity feeds.
- **Marketplace & Payments**: Sell original songs online with a integrated PayPal checkout gateway and customer order history.
- **Live Music Streaming**: Schedule and broadcast live music events using YouTube streaming integration.
- **Songwriting & Organization Tools**: Personal rich-text notes with TinyMCE and customizable playlists.
- **Real-Time Communication**: Instant notifications and real-time private messaging powered by Node.js, Express, Socket.IO, and Redis Pub/Sub.
- **Admin Control Center**: Built-in administration portal (Gentelella & Voyager) to oversee user accounts, track uploads, playlists, genre tags, copyright reports, and site analytics.

---

## 🏗️ Architecture & Technology Stack

| Layer | Technologies |
| :--- | :--- |
| **Backend Framework** | Laravel 5.5 (PHP 7.4) |
| **Frontend UI** | Vue.js 2.x, Blade Templates, Bootstrap 3, Gentelella Theme, Vue-APlayer, FontAwesome |
| **Real-Time Engine** | Node.js 14, Express, Socket.IO 2.x, Redis Pub/Sub, Laravel Echo |
| **Database & Cache** | MariaDB 10.6, Redis Alpine |
| **Payments** | PayPal REST API SDK (`anouar/paypalpayment`) |
| **Containerization** | Docker & Docker Compose |
| **Automated Testing** | PHPUnit 6.5, Puppeteer Core (E2E UI browser automation) |

---

## 📋 Prerequisites

Before starting, ensure you have the following installed on your machine:

- **Docker** and **Docker Compose** *(Recommended - runs the entire stack in isolated containers)*
- *Or for native/local setup without Docker:*
  - PHP 7.4 with extensions: `pdo_mysql`, `mbstring`, `zip`, `gd`, `xml`, `bcmath`, `pcntl`
  - Composer 1.x (or Composer 2.x with `--ignore-platform-reqs`)
  - MariaDB 10.4+ or MySQL 5.7+
  - Redis Server 5+
  - Node.js 14.x (for asset compiling and socket server) & npm
  - Google Chrome (for automated E2E testing)

---

## 🚀 Setup Instructions

### Option A: Docker Setup (Recommended & Fastest)

The repository includes a multi-container Docker configuration with healthy service orchestration:
- **`app`**: PHP 7.4 CLI serving Laravel on `http://localhost:8000`
- **`db`**: MariaDB 10.6 on port `3306`
- **`redis`**: Redis Alpine on port `6379`
- **`socket`**: Node.js Socket.IO server on port `8890`

#### 1. Clone the Repository & Configure Environment
```bash
git clone https://github.com/zugger14/musicbook.git
cd musicbook

# Create your .env file from the example template
cp .env.example .env
```

Ensure your `.env` contains the Docker service hostnames:
```env
APP_NAME=MusicBook
APP_ENV=local
APP_KEY=base64:swZxe20ngCOQNyb2TemredEkALzk+i0ainMUpbB4irc=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=musicbook
DB_USERNAME=musicbook
DB_PASSWORD=secret

BROADCAST_DRIVER=redis
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

REDIS_HOST=redis
REDIS_PORT=6379
```

#### 2. Start the Docker Services
```bash
docker compose up -d --build
```
> The `app` service automatically waits until the MariaDB container passes its internal healthcheck before starting.

#### 3. Install PHP Dependencies (If not already installed)
```bash
docker compose exec app composer install
```

#### 4. Generate Application Key
```bash
docker compose exec app php artisan key:generate
```

#### 5. Run Database Migrations & Initial Seeders
```bash
# Run database schema migrations
docker compose exec app php artisan migrate

# Seed administrative account and initial user models
docker compose exec app php artisan db:seed
```

#### 6. Seed Test Artist & Audio Fixture
```bash
# Creates dummyartist@test.com and populates a playable audio track in storage
docker compose exec app php test_seed.php
```

#### 7. Create Public Storage Symlink & Permissions
```bash
docker compose exec app php artisan storage:link
docker compose exec app chmod -R 777 storage bootstrap/cache
```

#### 8. Access the Application
- **Main Web Application**: [http://localhost:8000](http://localhost:8000)
- **Admin Gateway**: [http://localhost:8000/admin/login](http://localhost:8000/admin/login)
- **Socket.IO Real-Time Service**: `http://localhost:8890`

---

### Option B: Local Setup (Without Docker)

#### 1. Environment & Dependencies
```bash
cp .env.example .env
# Edit .env to set DB_HOST=127.0.0.1, DB_USERNAME, DB_PASSWORD, and REDIS_HOST=127.0.0.1

composer install
npm install
```

#### 2. Initialize Database & Storage
```bash
# Ensure MySQL/MariaDB and Redis services are running locally
php artisan key:generate
php artisan migrate --seed
php test_seed.php
php artisan storage:link
chmod -R 777 storage bootstrap/cache
```

#### 3. Compile Frontend Assets (Optional if editing Vue/Sass)
```bash
npm run dev
# or for production bundle:
npm run production
```

#### 4. Run the Servers
Open two separate terminal windows:
```bash
# Terminal 1: Laravel Web Server
php artisan serve --port=8000

# Terminal 2: Socket.IO Server
cd node_server
npm install
node server.js
```

---

## 👥 Default Test Accounts & Credentials

The seeders pre-configure the following test accounts:

| Persona | Email | Password | Role / Description | Access URL |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | `admin@gmail.com` | `secret` | System Administrator (Full back-office control) | [http://localhost:8000/admin/login](http://localhost:8000/admin/login) |
| **Artist** | `dummyartist@test.com` | `password` | Musician persona (`is_artist=1`), upload rights, songwriting tools | [http://localhost:8000/login](http://localhost:8000/login) |
| **Fan / Listener** | `dooley.shannon@example.com` | `secret` | Listener persona (`is_artist=0`), collections, payments | [http://localhost:8000/login](http://localhost:8000/login) |
| **Dummy Users** | *40 seeded users* | `secret` | Auto-generated community members with profiles | [http://localhost:8000/login](http://localhost:8000/login) |

---

## 🧪 Process for Testing the App

The testing suite consists of automated unit tests, controller script checks, automated end-to-end (E2E) browser UI tests, and a manual QA procedure.

### 1. Automated PHPUnit Tests
Runs Laravel backend unit and feature assertion tests.

- **Via Docker**:
  ```bash
  docker compose exec app vendor/bin/phpunit
  ```
- **Via Local Terminal**:
  ```bash
  vendor/bin/phpunit
  ```

### 2. Backend Controller & Audio Feed Verification
Validates that song feeds, database relationships, and audio streaming endpoints return valid JSON and playable URLs:

- **Via Docker**:
  ```bash
  docker compose exec app php test_endpoints.php
  ```
- **Via Local Terminal**:
  ```bash
  php test_endpoints.php
  ```

### 3. Automated End-to-End (E2E) Browser Tests (Puppeteer)
Automated UI test scripts using Chrome Puppeteer Core to verify end-to-end functionality across all user personas.

#### Quick Smoke Test
Tests authentication, redirection to artist feed, and APlayer playback initialization:
```bash
npm test
# or: node test_puppeteer.js
```

#### Comprehensive Full-Suite E2E Test
Executes **41 automated user actions** across all four personas (Guest, Artist, Fan, Admin), saves high-resolution screenshots to `tests/screenshots/`, and generates a detailed summary table:
```bash
npm run test:e2e
# or: node test_e2e_full.js
```

*Optional environment variables for E2E testing:*
- `BASE_URL`: Base target URL (defaults to `http://localhost:8000`)
- `CHROME_PATH`: Custom path to Google Chrome binary (defaults to auto-detection on macOS, Linux, or Windows)
- `SCREENSHOT_DIR`: Directory to save test screenshots (defaults to `./tests/screenshots`)

Example:
```bash
BASE_URL=http://localhost:8000 npm run test:e2e
```

---

### 4. Manual Testing QA Process

Follow this structured step-by-step procedure to manually test all core workflows in your browser:

#### Step 1: Public Guest Experience
1. Open [http://localhost:8000](http://localhost:8000) in your browser.
2. **Carousel Banner**: Click the carousel next/previous slide controls to confirm smooth image cycling.
3. **Public Player**: Locate the audio card on the landing page and click the inline **Play** button. Confirm that the audio stream starts and the waveform/progress bar moves.
4. **Auth Links**: Click **Sign Up** to inspect the registration form at `/register`, and **Login** to inspect `/login`.

#### Step 2: Artist Flow (Upload, Social Feed & Tools)
1. Navigate to `/login` and sign in with:
   - **Email**: `dummyartist@test.com`
   - **Password**: `password`
2. **Feed & Audio Playback** (`/artist/home`):
   - Click the play button on songs in the central feed.
   - Click **Like** to toggle the like counter and icon.
   - Type a comment into the comment input field and press Enter. Verify the comment appears immediately below the track.
3. **Song Upload**:
   - Click the **Upload Song** button in the navigation bar.
   - In the modal dialog, enter a song title, description, and genre tags.
   - Attach an audio file (`.wav` or `.mp3`) and set visibility to Public.
   - Submit the form and verify that the track appears on your feed and in `/tracks`.
4. **Tracks Management** (`/tracks`):
   - Review your uploaded tracks, edit metadata, or test playback.
5. **Personal Songwriting Notes** (`/notes/{user_id}`):
   - Click **Add Note**, enter a title and lyrics/chords in the rich-text editor, and click **Save**. Verify that the note is listed and readable.
6. **Playlists & Collections**:
   - Open `/playlist/{user_id}` and `/artist/collections` to verify playlist cards and saved song collections.
7. **Public Profile**:
   - Visit `/profile/dummy-artist` and verify avatar, track list, and stats. Click **Edit Profile** to verify form inputs.
8. **Real-Time Search & Messaging**:
   - Type a name into the **Search Users** bar in the header to observe live autocomplete dropdown results.
   - Visit `/inbox` to test private conversation threads.
9. **Log Out**:
   - Click the user dropdown in the top-right corner and select **Logout**.

#### Step 3: Fan / Listener Flow (Collections & Orders)
1. Navigate to `/login` and sign in with:
   - **Email**: `dooley.shannon@example.com`
   - **Password**: `secret`
2. **Fan Home** (`/fan/home`):
   - Verify the listener-oriented layout (artist upload controls are hidden).
3. **Artist Interaction**:
   - Visit an artist profile (e.g. `/profile/dummy-artist`).
   - Click **+fav / Follow** or **Add Friend** to verify social relationship actions.
4. **Collections & Payments**:
   - Visit `/fan/collections` to view purchased tracks and library bookmarks.
   - Visit `/payments` to view the payments dashboard, SSL badge, and order transaction records.
5. **Log Out**:
   - Click **Logout**.

#### Step 4: Admin Back-Office Flow
1. Navigate to [http://localhost:8000/admin/login](http://localhost:8000/admin/login) and sign in with:
   - **Email**: `admin@gmail.com`
   - **Password**: `secret`
2. **Admin Dashboard** (`/admin`):
   - Verify real-time summary statistic tiles (total registered users, songs, likes, comments, live events).
3. **User Management** (`/admin/users`):
   - Browse the DataTables list of users.
   - Click **Add User** (`/admin/createuser`) to verify the account creation form.
4. **Song Management** (`/admin/songs`):
   - View all songs across the system, or filter by Artist Songs (`/admin/artistsongs`) and Fan Songs (`/admin/fansongs`).
5. **Genre Tag Management** (`/admin/tags`):
   - Enter a new tag name (e.g. `"Electronic LoFi"`) and click **Add**. Confirm the new tag appears in the table.
6. **Playlists & Live Events** (`/admin/playlists`, `/admin/liveevents`):
   - Review user playlist records and scheduled YouTube live streams.
7. **Reports & Analytics** (`/admin/reports`):
   - Review reported tracks and user violation logs.
8. **Admin Logout**:
   - Click the top navigation logout control to end the administrative session.

---

## 📁 Key Directories & Project Structure

```
musicbook/
├── app/
│   ├── Http/Controllers/    # Application controllers (Song, Paypal, Profile, Admin, etc.)
│   ├── User.php             # User Eloquent model (Artist / Fan distinction)
│   ├── Song.php             # Song model with streaming and relationship methods
│   └── Admin.php            # Dedicated Admin authenticatable model
├── config/                  # Framework & package configuration files
├── database/
│   ├── migrations/          # Schema migrations for users, songs, tags, orders, etc.
│   ├── seeds/               # Database seeders (Admin, users, voyager tables)
│   └── factories/           # Model factories for testing
├── node_server/
│   ├── server.js            # Node.js Socket.IO + Express + Redis real-time server
│   └── package.json         # Real-time server dependencies
├── public/                  # Public web root (CSS, compiled JS, storage links)
├── resources/
│   ├── assets/js/           # Vue 2 components (Vue-APlayer, upload, comments, likes)
│   └── views/               # Blade templates organized by persona (guests, artists, fans, admin)
├── routes/
│   └── web.php              # Application web routes and middleware definitions
├── scratch/                 # Ad-hoc verification and test scripts
├── storage/                 # Uploaded songs, avatars, cached blade templates, and logs
├── tests/
│   ├── Feature/             # PHPUnit feature test suite
│   ├── Unit/                # PHPUnit unit test suite
│   └── screenshots/         # Automated E2E Puppeteer test screenshots & results
├── test_e2e_full.js         # Comprehensive 4-persona Puppeteer E2E test runner
├── test_puppeteer.js        # Quick smoke test runner
├── test_seed.php            # Seed script for dummy artist & audio fixture
├── test_endpoints.php       # Controller endpoint validation script
├── Dockerfile               # PHP 7.4 Bullseye container definition
├── docker-compose.yml       # Multi-container orchestration (app, db, redis, socket)
└── package.json             # NPM dependencies & test scripts
```

---

## 🛠️ Troubleshooting & Frequently Asked Questions

### 1. Audio tracks return 404 or do not play
- Verify that the storage symlink has been created:
  ```bash
  docker compose exec app php artisan storage:link
  ```
- Ensure file permissions on `storage` and `bootstrap/cache` are writable:
  ```bash
  docker compose exec app chmod -R 777 storage bootstrap/cache
  ```
- Re-run `test_seed.php` to populate a sample `.wav` track into `storage/app/public/songs/`.

### 2. Large song uploads fail (HTTP 413 or generic upload error)
- The PHP configuration in the Docker container (`/usr/local/etc/php/conf.d/uploads.ini`) sets:
  ```ini
  upload_max_filesize = 100M
  post_max_size = 100M
  memory_limit = 256M
  ```
- Ensure any audio files uploaded are within the configured limits.

### 3. Real-time notifications / friend requests not receiving events
- Ensure the `socket` container is active:
  ```bash
  docker compose ps
  ```
- Confirm that Redis is running on port 6379 and `.env` has:
  ```env
  BROADCAST_DRIVER=redis
  REDIS_HOST=redis
  ```

### 4. Running Puppeteer on Linux / CI environments
- If running `npm run test:e2e` in a headless Linux or CI container without Google Chrome installed in standard locations, set the `CHROME_PATH` environment variable:
  ```bash
  CHROME_PATH=/usr/bin/google-chrome npm run test:e2e
  ```

### 5. PayPal Payments in Sandbox Mode
- If `PAYPAL_CLIENT_ID` and `PAYPAL_CLIENT_SECRET` in `.env` are left blank, accessing `/payments` displays a user-friendly dashboard showing configuration status and payment options with graceful error handling. To test actual PayPal transactions, provide valid sandbox client credentials in `.env`.

---

## 📄 License
This project is open-source under the [MIT License](LICENSE).
