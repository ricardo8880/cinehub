<?php

// Set your AdSense IDs in .env. Campaigns can target home, post, blog, category, search or book.
return [
    'adsense_client' => env('ADSENSE_CLIENT', ''),
    'adsense_slot' => env('ADSENSE_SIDEBAR_SLOT', ''),
    'rotation_seconds' => 12,
    'campaigns' => [
        // ['title' => 'My book', 'description' => 'Learn more', 'url' => 'https://example.com',
        //  'image' => 'https://example.com/image.jpg', 'kind' => 'own', 'contexts' => ['home', 'post']],
        // kind: own or affiliate. Use only approved affiliate creatives and links.
    ],
];
