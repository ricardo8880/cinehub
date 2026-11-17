<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\PostController;
use App\Models\BlogCategory;
use App\Support\ArticleImageManager;
use App\Http\Controllers\Admin\GenreController;
use App\Models\Genre;
use App\Models\Post;
use App\Models\User;
use App\Support\MediaFileChanges;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class ContentStorageCleanupTest extends TestCase
{
    use DatabaseMigrations;

    public function test_post_deletion_removes_its_database_rows_and_images(): void
    {
        Storage::fake('public');
        $post = Post::create(['status' => 'draft', 'publication_type' => 'blog']);
        $post->localizations()->create([
            'locale' => 'en-us',
            'slug' => 'old-blog-post',
            'title' => 'Old blog post',
            'article_body' => '<p><img src="/storage/blog-content/9/en-us/old.webp"></p>',
        ]);
        $post->media()->create([
            'locale' => 'en-us',
            'type' => 'image',
            'thumbnail_path' => 'posts/'.$post->id.'/en-us/old.webp',
        ]);
        Storage::disk('public')->put('posts/'.$post->id.'/en-us/old.webp', 'image');
        Storage::disk('public')->put('blog-content/9/en-us/old.webp', 'image');

        app(PostController::class)->destroy($post);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $this->assertDatabaseMissing('post_localizations', ['post_id' => $post->id]);
        $this->assertDatabaseMissing('media', ['post_id' => $post->id]);
        Storage::disk('public')->assertMissing('posts/'.$post->id.'/en-us/old.webp');
        Storage::disk('public')->assertMissing('blog-content/9/en-us/old.webp');
    }

    public function test_post_deletion_removes_untracked_files_and_empty_post_folder(): void
    {
        Storage::fake('public');
        $post = Post::create(['status' => 'draft', 'publication_type' => 'blog']);
        $path = 'posts/'.$post->id.'/en-us/untracked.webp';
        Storage::disk('public')->put($path, 'abandoned file');

        app(PostController::class)->destroy($post);

        Storage::disk('public')->assertMissing($path);
        $this->assertFalse(Storage::disk('public')->directoryExists('posts/'.$post->id));
    }

    public function test_failed_database_change_removes_its_new_upload(): void
    {
        Storage::fake('public');
        $changes = app(MediaFileChanges::class);

        try {
            $changes->transaction(function (MediaFileChanges $changes): void {
                $changes->store(UploadedFile::fake()->create('image.gif', 5, 'image/gif'), 'posts/999/en-us');
                throw new RuntimeException('Simulated database failure');
            });
            $this->fail('The transaction should have failed.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Simulated database failure', $exception->getMessage());
        }

        $this->assertSame([], Storage::disk('public')->allFiles('posts'));
    }

    public function test_committed_media_deletion_removes_the_old_file(): void
    {
        Storage::fake('public');
        $post = Post::create(['status' => 'draft', 'publication_type' => 'standard']);
        $medium = $post->media()->create([
            'type' => 'image', 'thumbnail_path' => 'posts/'.$post->id.'/old.webp',
        ]);
        Storage::disk('public')->put('posts/'.$post->id.'/old.webp', 'image');

        app(MediaFileChanges::class)->transaction(function (MediaFileChanges $changes) use ($medium): void {
            $changes->retire($medium->thumbnail_path);
            $medium->delete();
        });

        Storage::disk('public')->assertMissing('posts/'.$post->id.'/old.webp');
    }

    public function test_deleted_genre_also_removes_saved_user_interests(): void
    {
        $genre = Genre::create(['name' => 'Unused', 'slug' => 'unused']);
        $user = User::factory()->create();
        $user->interests()->create(['kind' => 'genre', 'reference_id' => $genre->id]);

        app(GenreController::class)->destroy($genre);

        $this->assertDatabaseMissing('genres', ['id' => $genre->id]);
        $this->assertDatabaseMissing('user_interests', ['kind' => 'genre', 'reference_id' => $genre->id]);
    }

    public function test_orphan_pruning_retains_active_media(): void
    {
        Storage::fake('public');
        $post = Post::create(['status' => 'draft', 'publication_type' => 'standard']);
        $post->media()->create(['type' => 'image', 'thumbnail_path' => 'posts/'.$post->id.'/active.webp']);
        Storage::disk('public')->put('posts/'.$post->id.'/active.webp', 'active');
        Storage::disk('public')->put('posts/'.$post->id.'/orphan.webp', 'orphan');

        foreach (['active.webp', 'orphan.webp'] as $filename) {
            touch(Storage::disk('public')->path('posts/'.$post->id.'/'.$filename), time() - 8 * 86400);
        }

        clearstatcache();

        $this->artisan('cinehub:prune-media', ['--dry-run' => true])->assertExitCode(0);
        Storage::disk('public')->assertExists('posts/'.$post->id.'/orphan.webp');

        $this->artisan('cinehub:prune-media')->assertExitCode(0);
        Storage::disk('public')->assertExists('posts/'.$post->id.'/active.webp');
        Storage::disk('public')->assertMissing('posts/'.$post->id.'/orphan.webp');
    }
    public function test_post_quick_edit_preserves_additional_videos_and_images(): void
    {
        Storage::fake('public');
        [$post, $category] = $this->blogPostForMediaTests();
        $primary = $post->media()->create([
            'locale' => 'en-us', 'type' => 'trailer', 'provider' => 'youtube',
            'provider_id' => 'AAAAAAAAAAA', 'source_url' => 'https://youtu.be/AAAAAAAAAAA',
            'is_primary' => true,
        ]);
        $vimeo = $post->media()->create([
            'locale' => 'en-us', 'type' => 'trailer', 'provider' => 'vimeo',
            'provider_id' => '123456', 'is_primary' => false,
        ]);
        $image = $post->media()->create([
            'locale' => 'en-us', 'type' => 'image',
            'thumbnail_path' => 'posts/'.$post->id.'/additional.webp',
        ]);
        Storage::disk('public')->put($image->thumbnail_path, 'file');

        $this->updateBlogPost($post, $category, [
            'en-us' => [
                'id' => $primary->id,
                'type' => 'youtube',
                'youtube_url' => 'https://www.youtube.com/watch?v=BBBBBBBBBBB',
            ],
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertSame(3, $post->media()->count());
        $this->assertDatabaseHas('media', ['id' => $primary->id, 'provider_id' => 'BBBBBBBBBBB']);
        $this->assertDatabaseHas('media', ['id' => $vimeo->id, 'provider' => 'vimeo', 'provider_id' => '123456']);
        $this->assertDatabaseHas('media', ['id' => $image->id]);
        Storage::disk('public')->assertExists($image->thumbnail_path);
    }

    public function test_vimeo_media_is_unchanged_when_saving_the_post(): void
    {
        [$post, $category] = $this->blogPostForMediaTests();
        $vimeo = $post->media()->create([
            'locale' => 'en-us', 'type' => 'trailer', 'provider' => 'vimeo',
            'provider_id' => '998877', 'is_primary' => true,
        ]);
        $otherVideo = $post->media()->create([
            'locale' => 'en-us', 'type' => 'trailer', 'provider' => 'youtube',
            'provider_id' => 'AAAAAAAAAAA',
        ]);

        $this->updateBlogPost($post, $category, [
            'en-us' => ['id' => $vimeo->id, 'type' => 'keep'],
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertDatabaseHas('media', ['id' => $vimeo->id, 'provider' => 'vimeo', 'provider_id' => '998877']);
        $this->assertDatabaseHas('media', ['id' => $otherVideo->id]);
        $this->assertSame(2, $post->media()->count());
    }

    public function test_removing_a_selected_medium_keeps_other_media(): void
    {
        Storage::fake('public');
        [$post, $category] = $this->blogPostForMediaTests();
        $image = $post->media()->create([
            'locale' => 'en-us', 'type' => 'image',
            'thumbnail_path' => 'posts/'.$post->id.'/selected.webp', 'is_primary' => true,
        ]);
        $vimeo = $post->media()->create([
            'locale' => 'en-us', 'type' => 'trailer', 'provider' => 'vimeo',
            'provider_id' => '123456',
        ]);
        Storage::disk('public')->put($image->thumbnail_path, 'file');

        $this->updateBlogPost($post, $category, [
            'en-us' => ['id' => $image->id, 'type' => ''],
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('media', ['id' => $image->id]);
        $this->assertDatabaseHas('media', ['id' => $vimeo->id]);
        Storage::disk('public')->assertMissing($image->thumbnail_path);
    }

    public function test_media_manager_primary_flag_is_scoped_to_its_language(): void
    {
        [$post] = $this->blogPostForMediaTests();
        $oldEnglish = $post->media()->create([
            'locale' => 'en-us', 'type' => 'trailer', 'provider' => 'youtube',
            'provider_id' => 'AAAAAAAAAAA', 'is_primary' => true,
        ]);
        $french = $post->media()->create([
            'locale' => 'fr', 'type' => 'trailer', 'provider' => 'youtube',
            'provider_id' => 'BBBBBBBBBBB', 'is_primary' => true,
        ]);

        $this->post(route('admin.posts.media.store', $post), [
            'locale' => 'en-us', 'type' => 'trailer', 'provider' => 'vimeo',
            'provider_id' => '123456', 'is_primary' => '1',
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertDatabaseHas('media', ['id' => $oldEnglish->id, 'is_primary' => false]);
        $this->assertDatabaseHas('media', ['id' => $french->id, 'is_primary' => true]);
        $this->assertDatabaseHas('media', ['post_id' => $post->id, 'locale' => 'en-us', 'provider' => 'vimeo', 'is_primary' => true]);
    }

    public function test_recent_unsaved_editor_images_are_not_pruned(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $path = 'blog-content/'.$user->id.'/en-us/unsaved.webp';
        Storage::disk('public')->put($path, 'file');
        touch(Storage::disk('public')->path($path), time() - 3 * 86400);
        clearstatcache();

        app(ArticleImageManager::class)->cleanupOrphansForUser($user->id);
        $this->artisan('cinehub:prune-media')->assertExitCode(0);
        Storage::disk('public')->assertExists($path);

        touch(Storage::disk('public')->path($path), time() - 8 * 86400);
        clearstatcache();
        $this->artisan('cinehub:prune-media')->assertExitCode(0);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_post_editor_upload_is_persisted_only_on_successful_save(): void
    {
        Storage::fake('public');
        [$post, $category] = $this->blogPostForMediaTests();
        $token = str_repeat('a', 32);
        $this->put(route('admin.posts.update', $post), [
            'status' => 'draft',
            'publication_type' => 'blog',
            'blog_category_id' => $category->id,
            'localizations' => [
                'en-us' => [
                    'title' => 'Test post edited',
                    'article_body' => '<p><img src="/storage/blog-content/pending/'.$token.'.webp"></p>',
                ],
            ],
            'article_uploads' => [
                $token => UploadedFile::fake()->image('new.jpg'),
            ],
        ])->assertRedirect()->assertSessionHasNoErrors();

        $html = (string) $post->fresh()->localizations()->where('locale', 'en-us')->value('article_body');
        $this->assertStringNotContainsString('/pending/', $html);
        $this->assertCount(1, Storage::disk('public')->allFiles('blog-content'));
        $this->assertStringContainsString('/storage/'.Storage::disk('public')->allFiles('blog-content')[0], $html);
    }

    public function test_failed_editor_save_rolls_back_new_image(): void
    {
        Storage::fake('public');
        $changes = app(MediaFileChanges::class);
        $user = User::factory()->create();

        $this->expectException(RuntimeException::class);
        try {
            $changes->transaction(function (MediaFileChanges $changes) use ($user): void {
                $changes->storeArticle(UploadedFile::fake()->image('new.jpg'), $user->id, 'en-us');
                throw new RuntimeException('Cannot save article');
            });
        } finally {
            $this->assertSame([], Storage::disk('public')->allFiles('blog-content'));
        }
    }

    public function test_removing_article_image_deletes_its_file_and_empty_folders(): void
    {
        Storage::fake('public');
        [$post, $category] = $this->blogPostForMediaTests();
        $path = 'blog-content/42/en-us/old.webp';
        Storage::disk('public')->put($path, 'old');
        $post->localizations()->where('locale', 'en-us')->update([
            'article_body' => '<p><img src="/storage/'.$path.'"></p>',
        ]);

        $this->updateBlogPost($post, $category, [])->assertRedirect()->assertSessionHasNoErrors();
        Storage::disk('public')->assertMissing($path);
        $this->assertFalse(Storage::disk('public')->directoryExists('blog-content/42/en-us'));
    }

    public function test_failed_post_commit_deletion_remains_pending_and_can_be_retried(): void
    {
        Storage::fake('public');
        $post = Post::create(['status' => 'draft', 'publication_type' => 'standard']);
        $path = 'posts/'.$post->id.'/old.webp';
        $medium = $post->media()->create(['type' => 'image', 'thumbnail_path' => $path]);
        Storage::disk('public')->put($path, 'image');

        $changes = new class(app(\App\Support\OptimizedImageStorage::class)) extends MediaFileChanges {
            public function deleteFile(string $path): void
            {
                throw new RuntimeException('Disk failure simulated');
            }
        };

        try {
            $changes->transaction(function (MediaFileChanges $manager) use ($medium): void {
                $manager->retire($medium->thumbnail_path);
                $medium->delete();
            });
            $this->fail('The disk error must be reported.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('incomplete', $exception->getMessage());
        }

        $this->assertDatabaseMissing('media', ['id' => $medium->id]);
        $this->assertDatabaseHas('pending_media_deletions', ['path' => $path]);
        Storage::disk('public')->assertExists($path);

        $this->artisan('cinehub:retry-media-deletions')->assertExitCode(0);
        $this->assertDatabaseCount('pending_media_deletions', 0);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_failed_rollback_cleanup_is_recorded_for_retry(): void
    {
        Storage::fake('public');
        $changes = new class(app(\App\Support\OptimizedImageStorage::class)) extends MediaFileChanges {
            public function deleteFile(string $path): void
            {
                throw new RuntimeException('Disk failure simulated');
            }
        };

        $storedPath = null;
        try {
            $changes->transaction(function (MediaFileChanges $manager) use (&$storedPath): void {
                $storedPath = $manager->store(
                    UploadedFile::fake()->create('sample.gif', 5, 'image/gif'),
                    'posts/999/en-us',
                );
                throw new RuntimeException('Database failure simulated');
            });
            $this->fail('The transaction should fail.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('still require cleanup', $exception->getMessage());
        }

        $this->assertNotNull($storedPath);
        $this->assertDatabaseHas('pending_media_deletions', ['path' => $storedPath]);
        Storage::disk('public')->assertExists($storedPath);
        $this->artisan('cinehub:retry-media-deletions')->assertExitCode(0);
        $this->assertDatabaseCount('pending_media_deletions', 0);
        Storage::disk('public')->assertMissing($storedPath);
    }

    public function test_media_shared_with_another_post_is_not_deleted(): void
    {
        Storage::fake('public');
        $first = Post::create(['status' => 'draft', 'publication_type' => 'standard']);
        $second = Post::create(['status' => 'draft', 'publication_type' => 'standard']);
        $path = 'posts/'.$first->id.'/shared.webp';
        $firstMedium = $first->media()->create(['type' => 'image', 'thumbnail_path' => $path]);
        $second->media()->create(['type' => 'image', 'thumbnail_path' => $path]);
        Storage::disk('public')->put($path, 'shared');

        app(MediaFileChanges::class)->transaction(function (MediaFileChanges $changes) use ($firstMedium): void {
            $changes->retire($firstMedium->thumbnail_path);
            $firstMedium->delete();
        });

        Storage::disk('public')->assertExists($path);
        $this->assertDatabaseCount('pending_media_deletions', 0);
    }

    public function test_deleting_an_article_does_not_delete_a_shared_editor_image(): void
    {
        Storage::fake('public');
        $path = 'blog-content/9/en-us/shared.webp';
        Storage::disk('public')->put($path, 'shared');
        $first = Post::create(['status' => 'draft', 'publication_type' => 'blog']);
        $second = Post::create(['status' => 'draft', 'publication_type' => 'blog']);
        foreach ([$first, $second] as $post) {
            $post->localizations()->create([
                'locale' => 'en-us', 'slug' => 'shared-article-'.$post->id,
                'title' => 'Article '.$post->id,
                'article_body' => '<img src="/storage/'.$path.'">',
            ]);
        }

        app(PostController::class)->destroy($first);
        Storage::disk('public')->assertExists($path);
        $this->assertDatabaseCount('pending_media_deletions', 0);

        app(PostController::class)->destroy($second);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_manual_audit_can_remove_legacy_orphans_without_waiting_seven_days(): void
    {
        Storage::fake('public');
        $path = 'posts/999/en-us/legacy.webp';
        Storage::disk('public')->put($path, 'orphan');

        $this->artisan('cinehub:prune-media', ['--hours' => 0, '--dry-run' => true])->assertExitCode(0);
        Storage::disk('public')->assertExists($path);
        $this->artisan('cinehub:prune-media', ['--hours' => 0])->assertExitCode(0);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_removing_a_translation_removes_its_media_and_editor_files(): void
    {
        Storage::fake('public');
        [$post, $category] = $this->blogPostForMediaTests();
        $imagePath = 'posts/'.$post->id.'/fr/old.webp';
        $articlePath = 'blog-content/42/fr/old.webp';
        Storage::disk('public')->put($imagePath, 'image');
        Storage::disk('public')->put($articlePath, 'article');
        $post->localizations()->create([
            'locale' => 'fr', 'slug' => 'ancien-article', 'title' => 'Ancien article',
            'article_body' => '<img src="/storage/'.$articlePath.'">',
        ]);
        $post->media()->create([
            'locale' => 'fr', 'type' => 'image', 'thumbnail_path' => $imagePath,
        ]);
        $post->media()->create([
            'locale' => 'en-us', 'type' => 'trailer', 'provider' => 'vimeo',
            'provider_id' => '12345',
        ]);

        $this->updateBlogPost($post, $category, [])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('post_localizations', ['post_id' => $post->id, 'locale' => 'fr']);
        $this->assertDatabaseMissing('media', ['post_id' => $post->id, 'locale' => 'fr']);
        $this->assertDatabaseHas('media', ['post_id' => $post->id, 'locale' => 'en-us', 'provider' => 'vimeo']);
        Storage::disk('public')->assertMissing($imagePath);
        Storage::disk('public')->assertMissing($articlePath);
        $this->assertDatabaseCount('pending_media_deletions', 0);
    }

    private function blogPostForMediaTests(): array
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = BlogCategory::create(['name' => 'My Picks']);
        $post = Post::create([
            'status' => 'draft', 'publication_type' => 'blog',
            'blog_category_id' => $category->id, 'author_id' => $admin->id,
        ]);
        $post->localizations()->create([
            'locale' => 'en-us', 'slug' => 'test-post',
            'title' => 'Test post', 'article_body' => '<p>Test article.</p>',
        ]);
        $this->actingAs($admin, 'admin')->withSession(['admin.last_activity' => time()]);

        return [$post, $category];
    }

    private function updateBlogPost(Post $post, BlogCategory $category, array $media): \Illuminate\Testing\TestResponse
    {
        return $this->put(route('admin.posts.update', $post), [
            'status' => 'draft',
            'publication_type' => 'blog',
            'blog_category_id' => $category->id,
            'localizations' => [
                'en-us' => ['title' => 'Test post edited', 'article_body' => '<p>Test article.</p>'],
            ],
            'media' => $media,
        ]);
    }

}
