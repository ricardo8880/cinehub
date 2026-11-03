<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moderation_terms', function (Blueprint $table): void {
            $table->id();
            $table->string('phrase', 150);
            $table->string('locale', 8)->default('*');
            $table->string('classification', 12);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['phrase', 'locale']);
            $table->index(['locale', 'is_active']);
        });
    }

    public function down(): void { Schema::dropIfExists('moderation_terms'); }
};
