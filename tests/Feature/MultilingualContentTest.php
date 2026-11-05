<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultilingualContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        config()->set('seo.index_french', true);
        config()->set('seo.index_german', true);
    }

    public function test_translated_posts_are_indexed_only_with_local_content_taxonomy_and_media(): void
    {
        $category = Category::create(['name' => 'Movies', 'slug' => 'movies', 'is_active' => true]);
        foreach ([
            'en-us' => ['movies', 'Movies'],
            'fr' => ['films', 'Films'],
            'de' => ['filme', 'Filme'],
        ] as $locale => [$slug, $name]) {
            $category->localizations()->create([
                'locale' => $locale, 'slug' => $slug, 'name' => $name, 'is_active' => true,
            ]);
        }

        $post = Post::create([
            'status' => 'published', 'publication_type' => 'standard', 'published_at' => now()->subDay(),
        ]);
        $post->categories()->attach($category->id);
        $post->localizations()->create([
            'locale' => 'en-us', 'slug' => 'a-new-film', 'title' => 'A new film', 'is_active' => true,
        ]);
        $post->localizations()->create([
            'locale' => 'fr', 'slug' => 'un-nouveau-film', 'title' => 'Un nouveau film', 'is_active' => true,
        ]);
        $post->localizations()->create([
            'locale' => 'de', 'slug' => 'ein-neuer-film', 'title' => 'Ein neuer Film', 'is_active' => true,
        ]);
        $post->media()->create(['locale' => 'en-us', 'type' => 'image', 'is_primary' => true]);
        $post->media()->create(['locale' => 'fr', 'type' => 'image', 'is_primary' => true]);

        $this->get('/fr/films/un-nouveau-film')
            ->assertOk()
            ->assertSee('<html lang="fr"', false)
            ->assertSee('Un nouveau film')
            ->assertSee('hreflang="fr"', false)
            ->assertSee('hreflang="en"', false)
            ->assertDontSee('hreflang="de"', false);
        $this->get('/de/filme/ein-neuer-film')->assertNotFound();
        $this->get('/fr/movies/a-new-film')->assertNotFound();

        $this->get('/sitemap.xml')
            ->assertSee('/en/movies/a-new-film', false)
            ->assertSee('/fr/films/un-nouveau-film', false)
            ->assertDontSee('/de/filme/ein-neuer-film', false);

        $this->get('/fr/search?q=nouveau')->assertOk()->assertSee('Un nouveau film');
        $this->get('/de/search?q=nouveau')->assertOk()->assertDontSee('Un nouveau film');
    }

    public function test_books_are_independent_per_language_without_english_fallback(): void
    {
        SiteSetting::create(['key' => 'book_title', 'value' => 'English book']);
        SiteSetting::create(['key' => 'book_synopsis', 'value' => 'Original English content']);
        SiteSetting::create(['key' => 'book_title_de', 'value' => 'Deutsches Buch']);
        SiteSetting::create(['key' => 'book_synopsis_de', 'value' => 'Deutscher Inhalt']);

        $this->get('/en/book')->assertOk()->assertSee('English book')->assertDontSee('Deutscher Inhalt');
        $this->get('/fr/book')->assertNotFound();
        $this->get('/de/book')->assertOk()
            ->assertSee('Deutsches Buch')->assertSee('Deutscher Inhalt')
            ->assertDontSee('Original English content')
            ->assertSee('hreflang="en"', false)
            ->assertSee('hreflang="de"', false)
            ->assertDontSee('hreflang="fr"', false);

        $this->get('/sitemap.xml')
            ->assertSee('/en/book', false)
            ->assertSee('/de/book', false)
            ->assertDontSee('/fr/book', false);
    }

    public function test_french_and_german_interface_and_search_errors_do_not_fall_back_to_english(): void
    {
        $this->get('/fr')->assertOk()
            ->assertSee('window.cinehubMessages', false)
            ->assertSee('Dernières vidéos')
            ->assertDontSee('Trailers, synopses, reviews and entertainment discoveries.');
        $this->get('/de')->assertOk()
            ->assertSee('window.cinehubMessages', false)
            ->assertSee('Neueste Videos');
    }

    public function test_hidden_translations_are_not_listed_by_language_switcher_or_sitemap(): void
    {
        $this->get('/fr')->assertOk()->assertSee('Français')->assertSee('Deutsch');
        $this->get('/ja-jp')->assertNotFound();
    }
}
