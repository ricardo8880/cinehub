<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_terms', function (Blueprint $table): void {
            $table->id();
            $table->string('locale', 12);
            $table->string('term', 120);
            $table->string('normalized_term', 120);
            $table->unsignedBigInteger('search_count')->default(1);
            $table->unsignedInteger('last_result_count')->default(0);
            $table->dateTime('first_searched_at');
            $table->dateTime('last_searched_at');
            $table->timestamps();
            $table->unique(['locale', 'normalized_term']);
            $table->index(['locale', 'search_count']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_terms');
    }
};
