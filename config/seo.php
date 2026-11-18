<?php

return [
    // All three supported editorial languages are indexable by default.
    // Set SEO_INDEX_FRENCH=false or SEO_INDEX_GERMAN=false in .env to
    // temporarily disable a language during future editorial reviews.
    'index_french' => env('SEO_INDEX_FRENCH', true),
    'index_german' => env('SEO_INDEX_GERMAN', true),
];
