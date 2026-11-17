<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Speeds up the existence check for earlier visits of a visitor.
        // No historical data is changed or deleted.
        Schema::table('post_views', function (Blueprint $table): void {
            $table->index(['visitor_key', 'viewed_at'], 'post_views_returning_visitor_index');
        });
    }

    public function down(): void
    {
        Schema::table('post_views', function (Blueprint $table): void {
            $table->dropIndex('post_views_returning_visitor_index');
        });
    }
};
