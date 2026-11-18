<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Public listing pages filter by publication type/status and sort by date.
        Schema::table('posts', function (Blueprint $table): void {
            $table->index(['status', 'publication_type', 'published_at'], 'posts_public_listing_index');
        });

        // Post cards and localized archives check media availability repeatedly.
        Schema::table('media', function (Blueprint $table): void {
            $table->index(['post_id', 'locale', 'type'], 'media_post_locale_type_index');
        });
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table): void {
            $table->dropIndex('media_post_locale_type_index');
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropIndex('posts_public_listing_index');
        });
    }
};
