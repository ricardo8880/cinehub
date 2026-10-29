<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table): void {
            $table->string('seo_title')->nullable()->after('description');
            $table->string('seo_description', 320)->nullable()->after('seo_title');
        });

        Schema::table('post_localizations', function (Blueprint $table): void {
            $table->boolean('is_active')->default(true)->index()->after('locale');
        });

        Schema::table('media', function (Blueprint $table): void {
            $table->string('alt_text')->nullable()->after('thumbnail_path');
            $table->text('caption')->nullable()->after('alt_text');
            $table->unsignedInteger('sort_order')->default(0)->after('is_primary');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table): void {
            $table->dropColumn(['seo_title', 'seo_description']);
        });

        Schema::table('post_localizations', function (Blueprint $table): void {
            $table->dropColumn('is_active');
        });

        Schema::table('media', function (Blueprint $table): void {
            $table->dropColumn(['alt_text', 'caption', 'sort_order']);
        });
    }
};
