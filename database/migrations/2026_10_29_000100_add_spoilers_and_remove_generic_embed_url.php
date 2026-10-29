<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('post_localizations', 'spoilers')) {
            Schema::table('post_localizations', function (Blueprint $table): void {
                $table->longText('spoilers')->nullable()->after('review');
            });
        }

        if (Schema::hasColumn('media', 'embed_url')) {
            Schema::table('media', function (Blueprint $table): void {
                $table->dropColumn('embed_url');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('media', 'embed_url')) {
            Schema::table('media', function (Blueprint $table): void {
                $table->text('embed_url')->nullable()->after('provider_id');
            });
        }

        if (Schema::hasColumn('post_localizations', 'spoilers')) {
            Schema::table('post_localizations', function (Blueprint $table): void {
                $table->dropColumn('spoilers');
            });
        }
    }
};
