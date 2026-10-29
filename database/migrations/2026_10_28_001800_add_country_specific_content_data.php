<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_localizations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->string('slug');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->timestamps();

            $table->unique(['category_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });

        $defaultLocale = config('localization.default', 'en-us');
        $now = now();

        DB::table('categories')->orderBy('id')->get()->each(function ($category) use ($defaultLocale, $now): void {
            DB::table('category_localizations')->insert([
                'category_id' => $category->id,
                'locale' => $defaultLocale,
                'is_active' => (bool) $category->is_active,
                'slug' => $category->slug,
                'name' => $category->name,
                'description' => $category->description,
                'seo_title' => $category->seo_title ?? null,
                'seo_description' => $category->seo_description ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        Schema::table('post_localizations', function (Blueprint $table): void {
            $table->boolean('is_featured')->default(false)->index()->after('is_active');
        });

        DB::table('post_localizations')
            ->join('posts', 'posts.id', '=', 'post_localizations.post_id')
            ->where('posts.is_featured', true)
            ->update(['post_localizations.is_featured' => true]);

        Schema::table('comments', function (Blueprint $table): void {
            $table->string('locale', 10)->nullable()->after('post_id')->index();
            $table->index(['post_id', 'locale', 'status', 'created_at'], 'comments_post_locale_status_created_index');
        });

        Schema::table('reactions', function (Blueprint $table): void {
            $table->dropUnique(['post_id', 'user_id', 'type']);
            $table->dropUnique(['post_id', 'visitor_key', 'type']);
            $table->string('locale', 10)->nullable()->after('post_id')->index();
            $table->unique(['post_id', 'locale', 'user_id', 'type'], 'reactions_post_locale_user_type_unique');
            $table->unique(['post_id', 'locale', 'visitor_key', 'type'], 'reactions_post_locale_visitor_type_unique');
            $table->index(['post_id', 'locale', 'type', 'created_at'], 'reactions_post_locale_type_created_index');
        });

        Schema::table('post_views', function (Blueprint $table): void {
            $table->string('locale', 10)->nullable()->after('post_id')->index();
            $table->index(['post_id', 'locale', 'viewed_at'], 'post_views_post_locale_viewed_index');
        });
    }

    public function down(): void
    {
        Schema::table('post_views', function (Blueprint $table): void {
            $table->dropIndex('post_views_post_locale_viewed_index');
            $table->dropColumn('locale');
        });

        Schema::table('reactions', function (Blueprint $table): void {
            $table->dropUnique('reactions_post_locale_user_type_unique');
            $table->dropUnique('reactions_post_locale_visitor_type_unique');
            $table->dropIndex('reactions_post_locale_type_created_index');
            $table->dropColumn('locale');
            $table->unique(['post_id', 'user_id', 'type']);
            $table->unique(['post_id', 'visitor_key', 'type']);
        });

        Schema::table('comments', function (Blueprint $table): void {
            $table->dropIndex('comments_post_locale_status_created_index');
            $table->dropColumn('locale');
        });

        Schema::table('post_localizations', function (Blueprint $table): void {
            $table->dropColumn('is_featured');
        });

        Schema::dropIfExists('category_localizations');
    }
};
