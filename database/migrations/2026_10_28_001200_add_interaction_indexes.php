<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table): void {
            $table->index(['post_id', 'status', 'created_at'], 'comments_post_status_created_index');
        });

        Schema::table('reactions', function (Blueprint $table): void {
            $table->index(['post_id', 'type', 'created_at'], 'reactions_post_type_created_index');
        });

        Schema::table('post_views', function (Blueprint $table): void {
            $table->index(['post_id', 'viewed_at'], 'post_views_post_viewed_index');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table): void {
            $table->dropIndex('comments_post_status_created_index');
        });

        Schema::table('reactions', function (Blueprint $table): void {
            $table->dropIndex('reactions_post_type_created_index');
        });

        Schema::table('post_views', function (Blueprint $table): void {
            $table->dropIndex('post_views_post_viewed_index');
        });
    }
};
