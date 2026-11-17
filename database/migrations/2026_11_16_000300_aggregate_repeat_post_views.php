<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post_views', function (Blueprint $table): void {
            // Historical rows remain unchanged; NULL dates intentionally exclude them
            // from the unique constraint. This migration does not delete old statistics.
            $table->date('view_date')->nullable();
            $table->unsignedInteger('view_count')->default(1);
            $table->timestamp('first_viewed_at')->nullable();
            $table->unique(['post_id', 'locale', 'visitor_key', 'view_date'], 'post_views_daily_visitor_unique');
        });
    }

    public function down(): void
    {
        // Removing the counter would lose repeat views. Refuse a destructive rollback.
        if (DB::table('post_views')->where('view_count', '>', 1)->exists()) {
            throw new RuntimeException('Cannot roll back aggregated post views without losing counts. Restore from a database backup instead.');
        }

        Schema::table('post_views', function (Blueprint $table): void {
            $table->dropUnique('post_views_daily_visitor_unique');
            $table->dropColumn(['view_date', 'view_count', 'first_viewed_at']);
        });
    }
};
