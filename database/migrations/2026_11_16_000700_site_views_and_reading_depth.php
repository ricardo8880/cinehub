<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_page_views_daily', function (Blueprint $table): void {
            $table->id();
            $table->date('view_date');
            $table->string('locale', 12);
            $table->string('page_type', 32);
            $table->unsignedBigInteger('views')->default(0);
            $table->unique(['view_date', 'locale', 'page_type'], 'site_views_day_locale_type_unique');
        });
        Schema::table('page_engagement_daily', function (Blueprint $table): void {
            $table->unsignedBigInteger('read_start')->default(0);
            $table->unsignedBigInteger('read_middle')->default(0);
            $table->unsignedBigInteger('read_end')->default(0);
        });
    }

    public function down(): void
    {
        if (DB::table('site_page_views_daily')->exists() || DB::table('page_engagement_daily')->where(function ($q) {
            $q->where('read_start', '>', 0)->orWhere('read_middle', '>', 0)->orWhere('read_end', '>', 0);
        })->exists()) {
            throw new RuntimeException('Cannot roll back collected analytics data. Restore a database backup if necessary.');
        }
        Schema::table('page_engagement_daily', fn (Blueprint $table) => $table->dropColumn(['read_start', 'read_middle', 'read_end']));
        Schema::dropIfExists('site_page_views_daily');
    }
};
