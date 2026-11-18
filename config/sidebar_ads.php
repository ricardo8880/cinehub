<?php

// Set your AdSense IDs in .env. Campaigns can target home, post, blog, category, search or book.
return [
    'adsense_client' => env('ADSENSE_CLIENT', ''),
    // Separate AdSense unit IDs. Unconfigured positions remain visual placeholders.
    // Left rail: only on posts, categories and blog; hidden on mobile.
    'left_slots' => [
        env('ADSENSE_LEFT_SLOT_1', ''),
        env('ADSENSE_LEFT_SLOT_2', ''),
        env('ADSENSE_LEFT_SLOT_3', ''),
        env('ADSENSE_LEFT_SLOT_4', ''),
        env('ADSENSE_LEFT_SLOT_5', ''),
    ],
    // Right rail: three AdSense positions, interleaved with two own campaigns.
    // Preserve support for the older ADSENSE_SIDEBAR_SLOT configuration.
    'right_slots' => [
        env('ADSENSE_RIGHT_SLOT_1', env('ADSENSE_SIDEBAR_SLOT', '')),
        env('ADSENSE_RIGHT_SLOT_2', ''),
        env('ADSENSE_RIGHT_SLOT_3', ''),
    ],
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
