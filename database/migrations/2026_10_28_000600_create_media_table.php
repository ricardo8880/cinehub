<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 10)->nullable()->index();
            $table->string('type')->default('trailer')->index();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->text('source_url')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->boolean('is_primary')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
