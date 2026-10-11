<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\User;
use App\Song;

// Create dummy user
$user = User::where('email', 'dummyartist@test.com')->first();
if (!$user) {
    $user = new User;
    $user->email = 'dummyartist@test.com';
    $user->name = 'Dummy Artist';
    $user->password = bcrypt('password');
    $user->is_artist = 1;
    $user->gender = 1;
    $user->avatar = 'default.jpg';
    $user->slug = 'dummy-artist';
    $user->save();
}

// Source file
$sourceFile = file_exists(__DIR__ . '/test_sample.wav') ? __DIR__ . '/test_sample.wav' : '/tmp/4 Guitar.wav';

if (!file_exists($sourceFile)) {
    die("Source file not found!\n");
}

// File move to storage
$filename = time() . '.wav';
$location = storage_path('app/public/songs');
if (!file_exists($location)) {
    mkdir($location, 0777, true);
}
copy($sourceFile, $location . '/' . $filename);

// Create song
$song = new Song;
$song->title = 'Dummy Guitar Song - WAV Test';
$song->user_id = $user->id;
$song->upload_type = 'public';
$song->status = 'present';
$song->song_filename = $filename;
$song->save();

echo "Song uploaded successfully. File: " . $filename . "\n";
echo "Login as: dummyartist@test.com / password\n";
