<?php

namespace Chipslays\Telegraph\Types\Elements;

/**
 * Class NodeElement
 *
 * This object represents a DOM element node.
 */
class Element
{
    /**
     * @param string $tag
     * @param array $children
     * @param array $attrs
     * @return NodeElement
     */
    public static function new(string $tag, array $children = [], array $attrs = [])
    {
        return new NodeElement($tag, $children, $attrs);
    }

    /**
     * @param string|array $text
     * @param string $url
     * @return NodeElement
     */
    public static function link($text, string $url)
    {
        return self::new('a', (array) $text, ['href' => $url]);
    }

    /**
     * @param string|array $text
     * @return NodeElement
     */
    public static function em($text)
    {
        return self::new('em', (array) $text);
    }

    /**
     * @param string|array $text
     * @return NodeElement
     */
    public static function blockquote($text)
    {
        return self::new('blockquote', (array) $text);
    }

    /**
     * @param string|array $text
     * @return NodeElement
     */
    public static function h3($text)
    {
        return self::new('h3', (array) $text);
    }

    /**
     * @param string|array $text
     * @return NodeElement
     */
    public static function strong($text)
    {
        return self::new('strong', (array) $text);
    }

    /**
     * @param string|array $text
     * @return NodeElement
     */
    public static function h4($text)
    {
        return self::new('h4', (array) $text);
    }

    /**
     * @param string|array $text
     * @return NodeElement
     */
    public static function paragraph($text)
    {
        return self::new('p', (array) $text);
    }

    /**
     * @param string|array $text
     * @return NodeElement
     */
    public static function pre($text)
    {
        return self::new('pre', (array) $text);
    }

    /**
     * @param string/array $text
     * @return NodeElement
     */
    public static function code($text)
    {
        return self::new('code', (array) $text);
    }

    /**
     * @param string $text
     * @return NodeElement
     */
    public static function br()
    {
        return self::new('br');
    }

    /**
     * @param string $text
     * @return NodeElement
     */
    public static function hr()
    {
        return self::new('hr');
    }

    /**
     * @param string $src
     * @return NodeElement
     */
    public static function img(string $src)
    {
        return self::new('img', [], ['src' => $src]);
    }

    /**
     * @param string|array $children
     * @param string $class
     * @return NodeElement
     */
    public static function div($children = [], string $class = '')
    {
        return self::new('div', (array) $children, ['class' => $class]);
    }

    /**
     * @param string|array $children
     * @param string $class
     * @return NodeElement
     */
    public static function span($children = [], string $class = '')
    {
        return self::new('span', (array) $children, ['class' => $class]);
    }

    /**
     * Unordered list.
     *
     * @param array $children Array of list items.
     * @return NodeElement
     */
    public static function ul(array $children = [])
    {
        return self::new('ul', $children);
    }

    /**
     * Ordered list.
     *
     * @param array $children Array of list items.
     * @return NodeElement
     */
    public static function ol(array $children = [])
    {
        return self::new('ol', $children);
    }

    /**
     * @param string|array $text
     * @return NodeElement
     */
    public static function li($text)
    {
        return self::new('li', (array) $text);
    }

    /**
     * Lists.
     *
     * @param array $items
     * @param boolean $orderedList
     * @return void
     */
    public static function list(array $items = [], bool $orderedList = false)
    {
        $listType = $orderedList ? 'ol' : 'ul';

        $children = [];
        foreach ($items as $item) {
            $children[] = self::li($item)->toArray();
        }

        return self::{$listType}($children);
    }

    /**
     * Just text, nothing more.
     * It human readable alias for span.
     *
     * @param string $text
     * @return NodeElement
     */
    public static function text(string $text)
    {
        return self::span($text);
    }

    /**
     * @param string|array $text
     * @return NodeElement
     */
    public static function figcaption($text)
    {
        return self::new('figcaption', (array) $text);
    }

    /**
     * @param string|array $children
     * @return NodeElement
     */
    public static function figure($children)
    {
        return self::new('figure', (array) $children);
    }

    /**
     * @param string $src
     * @param string|array $children
     * @return NodeElement
     */
    public static function iframe(string $src, $children = [])
    {
        return self::new('iframe', (array) $children, ['src' => $src]);
    }

    /**
     * Picture with caption.
     *
     * @param string $src
     * @param string/array $caption
     * @return NodeElement
     */
    public static function picture(string $src, $caption = '')
    {
        $image = self::img($src)->toArray();

        $children = [
            self::div([$image], 'figure_wrapper')->toArray(),
            self::figcaption($caption)->toArray(),
        ];

        return self::new('figure', $children);
    }

    /**
     * Universal embed method.
     *
     * @param string $url
     * @param string $caption
     * @return NodeElement
     */
    public static function embed(string $name, string $url, string $caption = '')
    {
        return self::new('figure', [
            self::iframe('/embed/' . $name . '?url=' . rawurlencode($url))->toArray(),
            self::figcaption($caption)->toArray(),
        ]);
    }

    /**
     * Youtube video embed.
     *
     * @param string $video
     * @param string $caption
     * @return NodeElement
     */
    public static function youtube(string $video, string $caption = '')
    {
        return self::embed(__FUNCTION__, $video, $caption);
    }

    /**
     * Vimeo video embed.
     *
     * @param string $video
     * @param string $caption
     * @return NodeElement
     */
    public static function vimeo(string $video, string $caption = '')
    {
        return self::embed(__FUNCTION__, $video, $caption);
    }

    /**
     * Twitter embed.
     *
     * @param string $video
     * @param string $caption
     * @return NodeElement
     */
    public static function twitter(string $post, string $caption = '')
    {
        return self::embed(__FUNCTION__, $post, $caption);
    }
}
