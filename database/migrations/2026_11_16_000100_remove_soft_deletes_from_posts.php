<?php

use App\Models\Post;
use App\Support\MediaFileChanges;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('posts', 'deleted_at')) {
            return;
        }

        // Purge legacy soft-deleted posts BEFORE removing the only field that
        // identifies them. This migration is not rerun if already recorded as
        // applied; in that case restoration requires a pre-migration DB backup.
        DB::table('posts')
            ->whereNotNull('deleted_at')
            ->select('id')
            ->orderBy('id')
            ->chunkById(50, function ($rows): void {
                foreach ($rows as $row) {
                    $post = Post::query()->find($row->id);
                    if ($post !== null) {
                        app(MediaFileChanges::class)->deletePost($post);
                    }
                }
            });

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->softDeletes();
        });
    }
};
