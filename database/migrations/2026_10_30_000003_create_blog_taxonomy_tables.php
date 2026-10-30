<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120)->unique();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('blog_genres', function (Blueprint $table): void {
            $table->id();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('blog_genre_localizations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('blog_genre_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 12)->index();
            $table->string('name', 120);
            $table->string('slug', 140);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['blog_genre_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });

        Schema::create('blog_category_blog_genre', function (Blueprint $table): void {
            $table->foreignId('blog_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blog_genre_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['blog_category_id', 'blog_genre_id']);
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->foreignId('blog_category_id')->nullable()->after('publication_type')->constrained()->nullOnDelete();
        });

        Schema::create('blog_genre_post', function (Blueprint $table): void {
            $table->foreignId('blog_genre_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['blog_genre_id', 'post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_genre_post');

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('blog_category_id');
        });

        Schema::dropIfExists('blog_category_blog_genre');
        Schema::dropIfExists('blog_genre_localizations');
        Schema::dropIfExists('blog_genres');
        Schema::dropIfExists('blog_categories');
    }
};
