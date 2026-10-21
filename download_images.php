<?php

$images = [
    'crispy_calamari.jpg' => 'https://images.unsplash.com/photo-1599488615731-7e5c2823ff28?w=800&auto=format&fit=crop&q=80',
    'turkey_burger.jpg' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=800&auto=format&fit=crop&q=80',
    'seared_salmon.jpg' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=800&auto=format&fit=crop&q=80',
    'lava_cake.jpg' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=800&auto=format&fit=crop&q=80',
    'cheesecake.jpg' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=800&auto=format&fit=crop&q=80',
    'berry_lemonade.jpg' => 'https://images.unsplash.com/photo-1621263764928-df1444c5e859?w=800&auto=format&fit=crop&q=80',
    'caramel_macchiato.jpg' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=800&auto=format&fit=crop&q=80',
];

$dir = __DIR__ . '/../../storage/app/public/menu_items/';
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

foreach ($images as $filename => $url) {
    $target = $dir . $filename;
    echo "Downloading {$filename}...\n";
    $content = @file_get_contents($url);
    if ($content) {
        file_put_contents($target, $content);
        echo "Successfully saved {$filename}\n";
    } else {
        echo "Failed downloading {$filename}\n";
    }
}

echo "Image downloads complete.\n";
