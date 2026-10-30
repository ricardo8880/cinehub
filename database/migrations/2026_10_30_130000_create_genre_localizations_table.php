<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('genre_localizations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('genre_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('name', 120);
            $table->string('slug', 140);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['genre_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });

        $defaultLocale = config('localization.default');
        DB::table('genres')->orderBy('id')->get()->each(function ($genre) use ($defaultLocale): void {
            $base = Str::slug((string) $genre->name) ?: 'genre';
            $slug = $base;
            $suffix = 2;
            while (DB::table('genre_localizations')->where('locale', $defaultLocale)->where('slug', $slug)->exists()) {
                $slug = $base.'-'.$suffix++;
            }
            DB::table('genre_localizations')->insert([
                'genre_id' => $genre->id,
                'locale' => $defaultLocale,
                'name' => $genre->name,
                'slug' => $slug,
                'is_active' => (bool) $genre->is_active,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('genre_localizations');
    }
};
