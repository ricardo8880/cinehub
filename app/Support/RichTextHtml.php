<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;
use DOMXPath;

final class RichTextHtml
{
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'em', 'u', 's', 'blockquote',
        'ol', 'ul', 'li', 'h2', 'h3', 'a',
    ];

    private const EMPTY_BLOCK_TAGS = ['p', 'blockquote', 'h2', 'h3', 'li', 'ol', 'ul'];

    private const EMPTY_INLINE_TAGS = ['strong', 'em', 'u', 's', 'a'];

    public static function normalize(?string $html): ?string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return null;
        }

        if ($html === strip_tags($html)) {
            return self::fromPlainText($html);
        }

        if (! class_exists(DOMDocument::class)) {
            return self::normalizeWithoutDom($html);
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="UTF-8"><div id="rich-text-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD,
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = (new DOMXPath($document))->query('//*[@id="rich-text-root"]')->item(0);

        if (! $root instanceof DOMElement) {
            return null;
        }

        self::sanitizeChildren($root);
        self::normalizeTextWhitespace($root);
        self::removeEmptyBlocks($root);

        $clean = '';
        foreach ($root->childNodes as $child) {
            $clean .= $document->saveHTML($child);
        }

        $clean = trim($clean);

        return self::plainText($clean) === '' ? null : $clean;
    }

    public static function plainText(?string $html): string
    {
        $value = str_ireplace(['<br>', '<br/>', '<br />', '</p>', '</li>', '</h2>', '</h3>'], ' ', (string) $html);
        $value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = self::normalizeWhitespace($value);
        $value = preg_replace('/\s+/u', ' ', $value) ?? '';

        return trim($value);
    }

    private static function normalizeTextWhitespace(DOMNode $parent): void
    {
        for ($child = $parent->firstChild; $child !== null; $child = $child->nextSibling) {
            if ($child instanceof DOMText) {
                $child->nodeValue = self::normalizeWhitespace($child->nodeValue ?? '');
                continue;
            }

            self::normalizeTextWhitespace($child);
        }
    }

    private static function normalizeWhitespace(string $value): string
    {
        return str_replace(["\u{00A0}", "\u{202F}", "\u{2007}"], ' ', $value);
    }

    private static function sanitizeChildren(DOMNode $parent): void
    {
        for ($child = $parent->firstChild; $child !== null;) {
            $next = $child->nextSibling;

            if ($child->nodeType === XML_COMMENT_NODE) {
                $parent->removeChild($child);
                $child = $next;
                continue;
            }

            if ($child instanceof DOMElement) {
                $tag = strtolower($child->tagName);

                if (in_array($tag, ['script', 'style', 'iframe', 'object', 'embed'], true)) {
                    $parent->removeChild($child);
                    $child = $next;
                    continue;
                }

                self::sanitizeChildren($child);

                if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                    while ($child->firstChild) {
                        $parent->insertBefore($child->firstChild, $child);
                    }
                    $parent->removeChild($child);
                    $child = $next;
                    continue;
                }

                self::sanitizeAttributes($child, $tag);
            }

            $child = $next;
        }
    }

    private static function sanitizeAttributes(DOMElement $element, string $tag): void
    {
        $href = $tag === 'a' ? trim($element->getAttribute('href')) : '';

        while ($element->attributes->length > 0) {
            $element->removeAttributeNode($element->attributes->item(0));
        }

        if ($tag !== 'a' || $href === '' || ! self::isSafeHref($href)) {
            return;
        }

        $element->setAttribute('href', $href);
        $element->setAttribute('rel', 'noopener noreferrer');
    }

    private static function removeEmptyBlocks(DOMNode $parent): void
    {
        for ($child = $parent->firstChild; $child !== null;) {
            $next = $child->nextSibling;

            if ($child instanceof DOMElement) {
                self::removeEmptyBlocks($child);
                $tag = strtolower($child->tagName);

                if (in_array($tag, self::EMPTY_INLINE_TAGS, true) && trim(self::normalizeWhitespace($child->textContent ?? '')) === '') {
                    $parent->removeChild($child);
                } elseif (in_array($tag, self::EMPTY_BLOCK_TAGS, true) && self::elementIsEmpty($child)) {
                    $parent->removeChild($child);
                }
            }

            $child = $next;
        }
    }

    private static function elementIsEmpty(DOMElement $element): bool
    {
        $text = self::normalizeWhitespace($element->textContent ?? '');

        if (trim($text) !== '') {
            return false;
        }

        foreach ($element->childNodes as $child) {
            if ($child instanceof DOMElement && strtolower($child->tagName) !== 'br') {
                return false;
            }
        }

        return true;
    }

    private static function isSafeHref(string $href): bool
    {
        if (str_starts_with($href, '/') || str_starts_with($href, '#')) {
            return true;
        }

        $scheme = strtolower((string) parse_url($href, PHP_URL_SCHEME));

        return in_array($scheme, ['http', 'https', 'mailto'], true);
    }

    private static function fromPlainText(string $text): ?string
    {
        $text = self::normalizeWhitespace($text);
        $text = trim(str_replace(["\r\n", "\r"], "\n", $text));

        if ($text === '') {
            return null;
        }

        $paragraphs = preg_split('/\n{2,}/', $text) ?: [];

        $html = '';

        foreach ($paragraphs as $paragraph) {
            $escaped = htmlspecialchars(trim($paragraph), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

            if ($escaped !== '') {
                $html .= '<p>'.nl2br($escaped, false).'</p>';
            }
        }

        return $html !== '' ? $html : null;
    }

    private static function normalizeWithoutDom(string $html): ?string
    {
        $html = preg_replace('/<(script|style|iframe|object|embed)\b[^>]*>.*?<\/\1>/is', '', $html) ?? '';
        $html = str_ireplace(['&nbsp;', '&#160;', '&#xA0;'], ' ', $html);
        $html = self::normalizeWhitespace($html);
        $html = strip_tags($html, '<p><br><strong><em><u><s><blockquote><ol><ul><li><h2><h3><a>');
        $html = preg_replace_callback('/<([a-z0-9]+)\b([^>]*)>/i', function (array $match): string {
            $tag = strtolower($match[1]);

            if ($tag !== 'a') {
                return '<'.$tag.'>';
            }

            $href = '';
            if (preg_match('/\bhref\s*=\s*(["\'])(.*?)\1/is', $match[2], $hrefMatch)) {
                $href = html_entity_decode(trim($hrefMatch[2]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            } elseif (preg_match('/\bhref\s*=\s*([^\s>]+)/i', $match[2], $hrefMatch)) {
                $href = html_entity_decode(trim($hrefMatch[1], "\"'"), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }

            if ($href === '' || ! self::isSafeHref($href)) {
                return '<a>';
            }

            return '<a href="'.htmlspecialchars($href, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'" rel="noopener noreferrer">';
        }, $html) ?? '';
        $empty = '(?:\s|&nbsp;|&#160;|&#xA0;|<br\s*\/?>)*';
        $html = preg_replace('/<(strong|em|u|s|a)>'.$empty.'<\/\1>/i', '', $html) ?? '';
        $html = preg_replace('/<(p|blockquote|h2|h3|li)>'.$empty.'<\/\1>/i', '', $html) ?? '';
        $html = preg_replace('/<(ol|ul)>\s*<\/\1>/i', '', $html) ?? '';
        $html = trim($html);

        return self::plainText($html) === '' ? null : $html;
    }
}
