<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_category_localizations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('blog_category_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('name', 120);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['blog_category_id', 'locale']);
        });

        $defaultLocale = config('localization.default');

        DB::table('blog_categories')->orderBy('id')->get()->each(function ($category) use ($defaultLocale): void {
            DB::table('blog_category_localizations')->insert([
                'blog_category_id' => $category->id,
                'locale' => $defaultLocale,
                'name' => $category->name,
                'is_active' => (bool) $category->is_active,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_category_localizations');
    }
};
