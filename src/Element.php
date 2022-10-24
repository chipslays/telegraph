<?php

namespace Telegraph;

use Telegraph\Types\NodeElement;

/**
 * Class helper for create node elements.
 */
class Element
{
    /**
     * Make <p> tag.
     *
     * @param string|NodeElement[] $text
     * @return NodeElement
     */
    public static function text(string|array $text): NodeElement
    {
        return new NodeElement('p', $text);
    }

    /**
     * Make <b> tag.
     *
     * @param string|NodeElement[] $text
     * @return NodeElement
     */
    public static function boldText(string|array $text): NodeElement
    {
        return new NodeElement('b', $text);
    }

    /**
     * Make <strong> tag.
     *
     * @param string|NodeElement[] $text
     * @return NodeElement
     */
    public static function strongText(string|array $text): NodeElement
    {
        return new NodeElement('strong', $text);
    }

    /**
     * Make <i> tag.
     *
     * @param string|NodeElement[] $text
     * @return NodeElement
     */
    public static function italicText(string|array $text): NodeElement
    {
        return new NodeElement('i', $text);
    }

    /**
     * Make <s> tag.
     *
     * @param string|NodeElement[] $text
     * @return NodeElement
     */
    public static function strikeText(string|array $text): NodeElement
    {
        return new NodeElement('s', $text);
    }

    /**
     * Make <u> tag.
     *
     * @param string|NodeElement[] $text
     * @return NodeElement
     */
    public static function underlineText(string|array $text): NodeElement
    {
        return new NodeElement('u', $text);
    }

    /**
     * Make <em> tag.
     *
     * @param string|NodeElement[] $text
     * @return NodeElement
     */
    public static function emphasizedText(string|array $text): NodeElement
    {
        return new NodeElement('em', $text);
    }

    /**
     * Make <h3> tag.
     *
     * @param string|NodeElement[] $text
     * @return NodeElement
     */
    public static function bigHeading(string|array $text): NodeElement
    {
        return new NodeElement('h3', $text);
    }

    /**
     * Make <h4> tag.
     *
     * @param string|NodeElement[] $text
     * @return NodeElement
     */
    public static function smallHeading(string|array $text): NodeElement
    {
        return new NodeElement('h4', $text);
    }

    /**
     * Make <ul> tag.
     *
     * @param NodeElement[] $childrens
     * @return NodeElement
     */
    public static function ul(array $childrens): NodeElement
    {
        return new NodeElement('ul', $childrens);
    }

    /**
     * Make <ol> tag.
     *
     * @param NodeElement[] $childrens
     * @return NodeElement
     */
    public static function ol(array $childrens): NodeElement
    {
        return new NodeElement('ol', $childrens);
    }

    /**
     * Make <li> tag.
     *
     * @param string|NodeElement[] $text
     * @return NodeElement
     */
    public static function li(string|array $text): NodeElement
    {
        return new NodeElement('li', $text);
    }

    /**
     * Make <blockquote> tag.
     *
     * @param string|NodeElement[] $text
     * @return NodeElement
     */
    public static function blockquote(string|array $text): NodeElement
    {
        return new NodeElement('blockquote', $text);
    }

    /**
     * Make a list <ul> or <ol> with <li> items.
     *
     * @param string[]|NodeElement[] $items
     * @param bool $numericList
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

    /**
     * Make <pre> tag.
     *
     * @param string|NodeElement[] $text
     * @return NodeElement
     */
    public static function pre(string|array $text): NodeElement
    {
        return new NodeElement('pre', $text);
    }

    /**
     * Make <code> tag.
     *
     * @param string|NodeElement[] $text
     * @return NodeElement
     */
    public static function code(string|array $text): NodeElement
    {
        return new NodeElement('code', $text);
    }

    /**
     * Make <hr> tag.
     *
     * @return NodeElement
     */
    public static function line(): NodeElement
    {
        return new NodeElement('hr');
    }

    /**
     * Make <br> tag.
     *
     * @return NodeElement
     */
    public static function space(): NodeElement
    {
        return new NodeElement('br');
    }

    /**
     * Make <a> tag.
     *
     * @param string|array $text
     * @param string|NodeElement[] $href
     * @return NodeElement
     */
    public static function link(string|array $text, string $href): NodeElement
    {
        return new NodeElement('a', $text, compact('href'));
    }

    /**
     * Make <img> tag.
     *
     * @param string $src
     * @param string|null $caption
     * @return NodeElement
     */
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
            $image,
            new NodeElement('figcaption', $caption),
        ]);
    }

    /**
     * Make embed iframe like youtube, vimeo, twitter, etc.
     *
     * @param string $vendorName
     * @param string $url
     * @param string $caption
     * @return NodeElement
     */
    public static function embed(string $vendorName, string $url, string $caption = ''): NodeElement
    {
        return new NodeElement('figure', [
            new NodeElement('iframe', attrs: ['src' => '/embed/' . $vendorName . '?url=' . rawurlencode($url)]),
            new NodeElement('figcaption', $caption),
        ]);
    }

    /**
     * Make Youtube video embed.
     *
     * @param string $video
     * @param string $caption
     * @return NodeElement
     */
    public static function youtube(string $video, string $caption = ''): NodeElement
    {
        return self::embed('youtube', $video, $caption);
    }

    /**
     * Make Vimeo video embed.
     *
     * @param string $video
     * @param string $caption
     * @return NodeElement
     */
    public static function vimeo(string $video, string $caption = ''): NodeElement
    {
        return self::embed('vimeo', $video, $caption);
    }

    /**
     * Make Twitter post embed.
     *
     * @param string $post
     * @param string $caption
     * @return NodeElement
     */
    public static function twitter(string $post, string $caption = ''): NodeElement
    {
        return self::embed('twitter', $post, $caption);
    }
}
