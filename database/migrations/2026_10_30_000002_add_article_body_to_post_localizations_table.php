<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post_localizations', function (Blueprint $table): void {
            $table->longText('article_body')->nullable()->after('review');
        });
    }

    public function down(): void
    {
        Schema::table('post_localizations', function (Blueprint $table): void {
            $table->dropColumn('article_body');
        });
    }
};
