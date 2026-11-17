<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_engagement_daily', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 12);
            $table->string('visitor_key', 64);
            $table->date('visit_date');
            $table->unsignedInteger('visit_count')->default(0);
            $table->unsignedBigInteger('duration_sum')->default(0);
            $table->unsignedBigInteger('active_sum')->default(0);
            $table->unsignedInteger('bounced')->default(0);
            $table->unique(['post_id', 'locale', 'visitor_key', 'visit_date'], 'engagement_daily_visitor_unique');
            $table->index(['visit_date', 'locale', 'post_id'], 'engagement_daily_period_index');
        });
    }

    public function down(): void
    {
        // Each daily row may represent multiple visits. Dropping populated
        // aggregates would silently destroy statistics.
        if (DB::table('page_engagement_daily')->exists()) {
            throw new RuntimeException('Daily engagement data exists; restore a backup to roll back without losing statistics.');
        }

        Schema::dropIfExists('page_engagement_daily');
    }
};
