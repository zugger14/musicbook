<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\User::where('email', 'dummyartist@test.com')->first();
Auth::guard('web')->login($user);

$controller = app('App\Http\Controllers\SongController');

echo "\n--- songFeeds ---\n";
echo json_encode($controller->songFeeds()->getData());

echo "\n\n--- getMostRecentSongs ---\n";
echo json_encode($controller->getMostRecentSongs());

