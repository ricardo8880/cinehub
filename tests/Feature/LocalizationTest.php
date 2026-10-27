<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocalizationTest extends TestCase
{
    public function test_root_redirects_to_the_default_locale(): void
    {
        $this->get('/')
            ->assertRedirect('/en-us');
    }

    public function test_supported_locales_are_accessible(): void
    {
        $this->get('/en-us')
            ->assertOk()
            ->assertSee('Latest Videos');

        $this->get('/en-gb')
            ->assertOk()
            ->assertSee('Latest Videos');
    }

    public function test_category_filter_is_accepted_without_changing_the_locale_route(): void
    {
        $this->get('/en-us?category=movies')
            ->assertOk()
            ->assertSee('Movies');
    }

    public function test_unsupported_locale_returns_not_found(): void
    {
        $this->get('/pt-br')->assertNotFound();
    }
}
