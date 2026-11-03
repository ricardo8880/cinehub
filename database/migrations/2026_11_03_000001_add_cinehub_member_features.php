<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('google_id')->nullable()->unique();
        });

        Schema::create('user_favorites', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'post_id']);
        });

        Schema::create('user_interests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('kind', 24);
            $table->unsignedBigInteger('reference_id');
            $table->timestamps();
            $table->unique(['user_id', 'kind', 'reference_id'], 'user_interests_unique');
            $table->index(['kind', 'reference_id']);
        });

        Schema::table('reactions', function (Blueprint $table): void {
            $table->unique(['post_id', 'locale', 'user_id', 'type'], 'reactions_user_post_locale_type_unique');
        });
        Schema::table('comments', function (Blueprint $table): void {
            $table->index(['post_id', 'locale', 'parent_id', 'status'], 'comments_public_thread_idx');
        });
    }

    public function down(): void
    {
        Schema::table('comments', fn (Blueprint $table) => $table->dropIndex('comments_public_thread_idx'));
        Schema::table('reactions', fn (Blueprint $table) => $table->dropUnique('reactions_user_post_locale_type_unique'));
        Schema::dropIfExists('user_interests');
        Schema::dropIfExists('user_favorites');
        Schema::table('users', fn (Blueprint $table) => $table->dropUnique(['google_id']));
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('google_id'));
    }
};
