<?php

// Set your AdSense IDs in .env. Campaigns can target home, post, blog, category, search or book.
return [
    'adsense_client' => env('ADSENSE_CLIENT', ''),
    'adsense_slot' => env('ADSENSE_SIDEBAR_SLOT', ''),
    'rotation_seconds' => 12,
    'campaigns' => [
        // ['kind' => 'own', 'contexts' => ['home', 'post'],
        //  'localizations' => [
        //      'en-us' => ['title' => 'My book', 'description' => 'Learn more', 'url' => 'https://example.com/en'],
        //      'fr' => ['title' => 'Mon livre', 'description' => 'En savoir plus', 'url' => 'https://example.com/fr'],
        //      'de' => ['title' => 'Mein Buch', 'description' => 'Mehr erfahren', 'url' => 'https://example.com/de'],
        //  ]],
        // A missing language edition is not advertised; legacy flat entries are English-only.
        // kind: own or affiliate. Use only approved affiliate creatives and links.
    ],
];
