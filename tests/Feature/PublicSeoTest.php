<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\Post;
use App\Models\PostLocalization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_english_uses_one_canonical_home_and_old_regions_redirect(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee('<html lang="en"', false)
            ->assertSee('rel="canonical" href="'.route('home', ['locale' => 'en-us']).'"', false)
            ->assertDontSee('hreflang="en-GB"', false);

        $this->get('/en-gb')->assertRedirect('/en');
        $this->get('/en-us')->assertRedirect('/en');
    }

    public function test_french_and_german_are_noindex_until_launch_flags_are_enabled(): void
    {
        config()->set('seo.index_french', false);
        config()->set('seo.index_german', false);

        foreach (['fr', 'de'] as $language) {
            $this->get('/'.$language)
                ->assertOk()
                ->assertSee('<meta name="robots" content="noindex, follow">', false);
        }

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/en</loc>', false)
            ->assertDontSee('/fr</loc>', false)
            ->assertDontSee('/de</loc>', false)
            ->assertDontSee('/en-us', false);

        config()->set('seo.index_french', true);
        config()->set('seo.index_german', true);

        $this->get('/fr')
            ->assertSee('<html lang="fr"', false)
            ->assertSee('hreflang="fr"', false)
            ->assertSee('hreflang="de"', false)
            ->assertDontSee('hreflang="fr-FR"', false);
        $this->get('/de')->assertSee('<html lang="de"', false);
        $this->get('/sitemap.xml')
            ->assertSee('/fr</loc>', false)
            ->assertSee('/de</loc>', false)
            ->assertDontSee('/ja-jp', false);
    }

    public function test_sitemap_only_lists_public_blog_articles(): void
    {
        $category = BlogCategory::create(['name' => 'Editorial', 'is_active' => true]);
        $category->localizations()->create([
            'locale' => 'en-us',
            'name' => 'Editorial',
            'is_active' => true,
        ]);

        $post = Post::create([
            'publication_type' => 'blog',
            'blog_category_id' => $category->id,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
        $localization = PostLocalization::create([
            'post_id' => $post->id,
            'locale' => 'en-us',
            'slug' => 'public-editorial',
            'title' => 'Public editorial',
            'is_active' => true,
        ]);

        $this->get('/sitemap.xml')
            ->assertSee('/en/blog/public-editorial', false)
            ->assertDontSee('/en-us/blog', false);

        $localization->update(['is_active' => false]);
        $this->get('/sitemap.xml')->assertDontSee('/en/blog/public-editorial', false);

        $localization->update(['is_active' => true]);
        $category->update(['is_active' => false]);
        $this->get('/sitemap.xml')->assertDontSee('/en/blog/public-editorial', false);
    }

    public function test_empty_blog_archive_is_noindex_and_out_of_range_page_is_404(): void
    {
        $this->get('/en/blog')
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, follow">', false);

        $this->get('/en/blog?page=999')->assertNotFound();
    }
}
