<?php

namespace Tests\Unit;

use App\Support\RichTextHtml;
use App\Support\SeoDescription;
use PHPUnit\Framework\TestCase;

class SeoEditorialTest extends TestCase
{
    public function test_automatic_description_stops_at_whole_word(): void
    {
        $source = 'In Middle-earth: Shadow of Mordor, revenge is only the beginning. Enter a brutal world where enemies remember your victories, your failures, and every scar you leave behind.';
        $description = SeoDescription::summarize($source);

        $this->assertLessThanOrEqual(160, mb_strlen($description));
        $this->assertStringEndsWith('you', $description);
        $this->assertStringStartsWith($description, $source);
    }

    public function test_legacy_cutoff_is_repaired_without_changing_manual_description(): void
    {
        $source = 'In Middle-earth: Shadow of Mordor, revenge is only the beginning. Enter a brutal world where enemies remember your victories, your failures, and every scar you leave behind. In Mordor, even death cannot erase a rivalry.';
        $legacy = mb_substr($source, 0, 170);

        $this->assertSame(SeoDescription::summarize($source), SeoDescription::resolve($legacy, $source));
        $this->assertSame('A carefully written summary.', SeoDescription::resolve('A carefully written summary.', $source));
    }

    public function test_spoiler_text_is_rendered_as_paragraphs_and_escaped(): void
    {
        $this->assertSame(
            '<p>First revelation.</p><p>Second revelation.</p>',
            RichTextHtml::normalizeSpoilers("First revelation.\n\nSecond revelation."),
        );
        $this->assertSame('<p>One &amp; two.</p>', RichTextHtml::normalizeSpoilers('One & two.'));
        $this->assertSame('<p>Legitimate <strong>emphasis</strong>.</p>', RichTextHtml::normalizeSpoilers('<p>Legitimate <strong>emphasis</strong>.</p>'));
    }
}
