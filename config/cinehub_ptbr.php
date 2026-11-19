<?php

return [
    // Keep Brazilian pages out of search indexes until PT-BR articles,
    // editorial metadata and legal copy have been published and reviewed.
    // Enable in .env as CINEHUB_PTBR_INDEX=true when they are ready.
    'index' => (bool) env('CINEHUB_PTBR_INDEX', false),
];
