<?php

namespace Telegraph;

use Telegraph\Types\NodeElement;

/**
 * Class helper for create node elements.
 */
class Element
{
    public static function text(string|array $text): NodeElement
    {
        return new NodeElement('p', $text);
    }

    public static function boldText(string|array $text): NodeElement
    {
        return new NodeElement('b', $text);
    }

    public static function strongText(string|array $text): NodeElement
    {
        return new NodeElement('strong', $text);
    }

    public static function italicText(string|array $text): NodeElement
    {
        return new NodeElement('i', $text);
    }

    public static function strikeText(string|array $text): NodeElement
    {
        return new NodeElement('s', $text);
    }

    public static function underlineText(string|array $text): NodeElement
    {
        return new NodeElement('u', $text);
    }

    public static function emphasizedText(string|array $text): NodeElement
    {
        return new NodeElement('em', $text);
    }

    public static function bigHeading(string|array $text): NodeElement
    {
        return new NodeElement('h3', $text);
    }

    public static function smallHeading(string|array $text): NodeElement
    {
        return new NodeElement('h4', $text);
    }

    public static function ul(array $childrens): NodeElement
    {
        return new NodeElement('ul', $childrens);
    }

    public static function ol(array $childrens): NodeElement
    {
        return new NodeElement('ol', $childrens);
    }

    public static function li(string|array $text): NodeElement
    {
        return new NodeElement('li', $text);
    }

    public static function blockquote(string|array $text): NodeElement
    {
        return new NodeElement('blockquote', $text);
    }

    /**
     * @param string[]|NodeElement[] $items
     * @param boolean $numericList
     * @return NodeElement
     */
    public static function list(array $items, $numericList = false): NodeElement
    {
        $childrens = [];

        foreach ($items as $item) {
            $childrens[] = self::li($item);
        }

        return $numericList ? self::ol($childrens) : self::ul($childrens);
    }

    public static function pre(string|array $text): NodeElement
    {
        return new NodeElement('pre', $text);
    }

    public static function code(string|array $text): NodeElement
    {
        return new NodeElement('code', $text);
    }

    public static function line(): NodeElement
    {
        return new NodeElement('hr');
    }

    public static function space(): NodeElement
    {
        return new NodeElement('br');
    }

    public static function link(string|array $text, string $href): NodeElement
    {
        return new NodeElement('a', $text, compact('href'));
    }

    public static function image(string $src, ?string $caption = null): NodeElement
    {
        if (!str_starts_with($src, 'http')) {
            $src = File::upload($src);
        }

        $image = new NodeElement('img', attrs: compact('src'));

        if (!$caption) {
            return $image;
        }

        return new NodeElement('figure', [
            new NodeElement('div', [$image], forceTag: true),
            new NodeElement('figcaption', $caption),
        ]);
    }

    public static function embed(string $vendorName, string $url, string $caption = ''): NodeElement
    {
        return new NodeElement('figure', [
            new NodeElement('iframe', attrs: ['src' => '/embed/' . $vendorName . '?url=' . rawurlencode($url)]),
            new NodeElement('figcaption', $caption),
        ]);
    }

    public static function youtube(string $video, string $caption = ''): NodeElement
    {
        return self::embed('youtube', $video, $caption);
    }

    public static function vimeo(string $video, string $caption = ''): NodeElement
    {
        return self::embed('vimeo', $video, $caption);
    }

    public static function twitter(string $post, string $caption = ''): NodeElement
    {
        return self::embed('twitter', $post, $caption);
    }
}
