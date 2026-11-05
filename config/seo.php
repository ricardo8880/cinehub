<?php

return [
    // Keep new languages out of Google's index until their navigation,
    // editorial translations and legal pages have been reviewed for launch.
    // Their URLs remain available for editorial preview while noindexed.
    'index_french' => env('SEO_INDEX_FRENCH', false),
    'index_german' => env('SEO_INDEX_GERMAN', false),
];
