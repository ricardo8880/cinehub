<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Aggregate session recognition without creating a row per page view.
        // Existing post views and historical metrics remain unchanged.
        Schema::create('visitor_activity_daily', function (Blueprint $table): void {
            $table->id();
            $table->string('visitor_key', 64);
            $table->date('activity_date');
            $table->dateTime('first_seen_at');
            $table->dateTime('last_seen_at');
            $table->dateTime('first_returned_at')->nullable();
            $table->unique(['visitor_key', 'activity_date'], 'visitor_activity_key_day_unique');
            $table->index(['first_returned_at', 'visitor_key'], 'visitor_activity_returns_index');
        });
    }

    public function down(): void
    {
        if (DB::table('visitor_activity_daily')->exists()) {
            throw new RuntimeException('Cannot discard recorded visitor activity. Restore a database backup if a rollback is required.');
        }

        Schema::dropIfExists('visitor_activity_daily');
    }
};
