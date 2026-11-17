<?php

return [
    'name' => env('SITE_NAME', 'MrRCine'),
    'brand_primary' => env('SITE_BRAND_PRIMARY', 'MrR'),
    'brand_accent' => env('SITE_BRAND_ACCENT', 'Cine'),
    'description' => env('SITE_DESCRIPTION', 'Trailers, synopses, reviews and entertainment discoveries.'),
    'google_site_verification' => env('GOOGLE_SITE_VERIFICATION'),
    'google_analytics_id' => env('GOOGLE_ANALYTICS_ID'),
    'backup_enabled' => env('BACKUP_ENABLED', false),
    'backup_retention_days' => env('BACKUP_RETENTION_DAYS', 14),
];
