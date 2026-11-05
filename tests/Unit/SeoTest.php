<?php

namespace Tests\Unit;

use App\Support\Seo;
use Tests\TestCase;

class SeoTest extends TestCase
{
    public function test_language_generic_tags_share_one_url_per_language(): void
    {
        config()->set('seo.index_french', true);
        config()->set('seo.index_german', true);

        $this->assertSame('en', Seo::language('en-us'));
        $this->assertSame('fr', Seo::language('fr'));
        $this->assertSame('de', Seo::language('de'));
        $this->assertSame('/en', parse_url(route('home', ['locale' => 'en-us']), PHP_URL_PATH));
        $this->assertSame('/fr', parse_url(route('home', ['locale' => 'fr']), PHP_URL_PATH));
        $this->assertSame('/de', parse_url(route('home', ['locale' => 'de']), PHP_URL_PATH));

        $this->assertSame([
            'en' => 'https://cinehub.example/en',
            'fr' => 'https://cinehub.example/fr',
            'de' => 'https://cinehub.example/de',
        ], Seo::alternateLinks([
            'en-us' => 'https://cinehub.example/en',
            'en-gb' => 'https://cinehub.example/en', // Historic: no separate English canonical.
            'fr' => 'https://cinehub.example/fr',
            'de' => 'https://cinehub.example/de',
            'ja-jp' => 'https://cinehub.example/ja-jp', // Retired locale is not indexable.
        ]));
    }

    public function test_new_languages_must_be_explicitly_released_for_indexing(): void
    {
        config()->set('seo.index_french', false);
        config()->set('seo.index_german', false);
        $this->assertFalse(Seo::indexable('fr'));
        $this->assertFalse(Seo::indexable('de'));
        $this->assertFalse(Seo::indexable('ja-jp'));
        $this->assertSame(['en-us'], Seo::indexableLocales());

        config()->set('seo.index_french', true);
        $this->assertTrue(Seo::indexable('fr'));
        $this->assertFalse(Seo::indexable('de'));

        config()->set('seo.index_german', true);
        $this->assertTrue(Seo::indexable('de'));
        $this->assertSame(['en-us', 'fr', 'de'], Seo::indexableLocales());
    }
}
