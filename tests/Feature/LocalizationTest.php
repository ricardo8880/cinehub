<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_root_redirects_to_the_single_english_language_url(): void
    {
        $this->get('/')->assertRedirect('/en');
    }

    public function test_supported_language_homes_are_accessible_and_translated(): void
    {
        $this->get('/en')->assertOk()->assertSee('<html lang="en"', false)->assertSee('Latest Videos');
        $this->get('/fr')->assertOk()->assertSee('<html lang="fr"', false)->assertSee('Dernières vidéos');
        $this->get('/de')->assertOk()->assertSee('<html lang="de"', false)->assertSee('Neueste Videos');
    }

    public function test_old_english_regional_urls_redirect_including_internal_paths(): void
    {
        foreach (['en-us', 'en-gb', 'en-au', 'en-ca'] as $region) {
            $this->get('/'.$region)->assertRedirect('/en');
            $this->get('/'.$region.'/search?q=movie')->assertRedirect('/en/search?q=movie');
        }
    }

    public function test_category_filter_is_accepted_without_changing_language_route(): void
    {
        $this->get('/en?category=movies')->assertOk()->assertSee('Movies');
    }

    public function test_unsupported_and_retired_test_locales_are_not_available(): void
    {
        $this->get('/pt-br')->assertNotFound();
        $this->get('/ja-jp')->assertNotFound();
        $this->get('/fr-fr')->assertNotFound();
        $this->get('/de-de')->assertNotFound();
    }
}
