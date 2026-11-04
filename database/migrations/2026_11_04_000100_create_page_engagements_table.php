<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('page_engagements', function (Blueprint $table): void {
            $table->id();
            $table->uuid('visit_token')->unique();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 12)->index();
            $table->string('visitor_key', 64)->nullable();
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->unsignedInteger('active_seconds')->default(0);
            $table->boolean('engaged')->default(false);
            $table->timestamp('started_at')->useCurrent()->index();
            $table->timestamp('updated_at')->nullable();
            $table->index(['post_id', 'locale', 'started_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_engagements');
    }
};
