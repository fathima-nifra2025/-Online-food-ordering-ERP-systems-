<?php
/**
 * Download food images from the internet and save them to the correct storage paths.
 * Run with: php download_food_images.php
 */
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\MenuItem;

$storagePath = storage_path('app/public/menu_items');

// Publicly accessible food images from Pexels (CDN, no auth required)
$images = [
    4  => [
        'filename' => 'turkey_burger.jpg',
        // Avocado Turkey Burger - pexels photo
        'url'      => 'https://images.pexels.com/photos/1556698/pexels-photo-1556698.jpeg?auto=compress&cs=tinysrgb&w=800',
    ],
    12 => [
        'filename' => 'berry_lemonade.jpg',
        // Berry Lemonade - pexels photo
        'url'      => 'https://images.pexels.com/photos/1337825/pexels-photo-1337825.jpeg?auto=compress&cs=tinysrgb&w=800',
    ],
    13 => [
        'filename' => 'caramel_macchiato.jpg',
        // Iced Caramel Coffee - pexels photo
        'url'      => 'https://images.pexels.com/photos/312418/pexels-photo-312418.jpeg?auto=compress&cs=tinysrgb&w=800',
    ],
];

// Context to suppress SSL errors for local dev environment
$context = stream_context_create([
    'http' => [
        'timeout'     => 30,
        'user_agent'  => 'Mozilla/5.0 (compatible; FoodERP/1.0)',
        'ignore_errors' => true,
    ],
    'ssl'  => [
        'verify_peer'      => false,
        'verify_peer_name' => false,
    ],
]);

foreach ($images as $id => $info) {
    $destPath = $storagePath . '/' . $info['filename'];

    if (file_exists($destPath)) {
        echo "Already exists: {$info['filename']}\n";
        continue;
    }

    echo "Downloading: {$info['filename']} from {$info['url']} ...\n";
    $data = @file_get_contents($info['url'], false, $context);

    if ($data === false || strlen($data) < 1000) {
        echo "  FAILED to download {$info['filename']}\n";
        continue;
    }

    file_put_contents($destPath, $data);
    $size = round(filesize($destPath) / 1024, 1);
    echo "  Saved ({$size} KB)\n";
}

echo "\nDone! Checking...\n";
foreach ($images as $id => $info) {
    $destPath = $storagePath . '/' . $info['filename'];
    $item = MenuItem::find($id);
    echo "  #{$id} {$item->name}: " . (file_exists($destPath) ? "✓ Image OK" : "✗ MISSING") . "\n";
}
