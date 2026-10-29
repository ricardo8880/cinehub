<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('posts')
            ->whereIn('status', ['scheduled', 'archived'])
            ->update([
                'status' => 'draft',
                'published_at' => null,
            ]);

        if (Schema::hasColumn('posts', 'type')) {
            Schema::table('posts', function (Blueprint $table): void {
                $table->dropColumn('type');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('posts', 'type')) {
            Schema::table('posts', function (Blueprint $table): void {
                $table->string('type')->default('editorial')->index();
            });
        }
    }
};
