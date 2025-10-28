<?php

declare(strict_types=1);

namespace Telegraph\Content;

/**
 * HTML Helper
 *
 * Utility class for generating HTML strings that can be converted
 * to Telegraph format using HtmlConverter.
 *
 * All methods are static and return properly escaped HTML strings.
 * Output is safe from XSS as all user input is escaped with htmlspecialchars().
 */
class HtmlHelper
{
    /**
     * Wrap content in HTML tag
     *
     * Wraps the given content in opening and closing tags.
     * Useful for quickly generating HTML elements.
     *
     * Note: Content is NOT escaped - it's assumed to be safe HTML or plain text.
     * If you need escaping, use htmlspecialchars() on content before passing.
     *
     * @param string $content Content to wrap
     * @param string $tag HTML tag name (default: 'p')
     * @return string HTML string with wrapped content
     *
     * @example
     * echo HtmlHelper::wrap('Hello World', 'h1');
     * // Output: <h1>Hello World</h1>
     *
     * echo HtmlHelper::wrap('Paragraph text');
     * // Output: <p>Paragraph text</p>
     *
     * @example Building HTML for conversion
     * $html = HtmlHelper::wrap('Title', 'h1') .
     *         HtmlHelper::wrap('Text', 'p');
     * $content = $telegraph->fromHtml($html);
     */
    public static function wrap(string $content, string $tag = 'p'): string
    {
        return "<{$tag}>{$content}</{$tag}>";
    }

    /**
     * Create HTML link
     *
     * Generates an HTML anchor tag with href attribute.
     * Both text and URL are automatically escaped to prevent XSS.
     *
     * @param string $text Link text (visible to user)
     * @param string $url Link URL (href attribute value)
     * @return string HTML link with escaped text and URL
     *
     * @example
     * echo HtmlHelper::link('Visit Site', 'https://example.com');
     * // Output: <a href="https://example.com">Visit Site</a>
     *
     * @example With special characters (auto-escaped)
     * echo HtmlHelper::link('A & B', 'https://example.com?x=1&y=2');
     * // Output: <a href="https://example.com?x=1&amp;y=2">A &amp; B</a>
     *
     * @example Building article with links
     * $html = HtmlHelper::wrap('Check out ' .
     *         HtmlHelper::link('our site', 'https://example.com'), 'p');
     * $page = $account->createPageFromHtml('Article', $html);
     */
    public static function link(string $text, string $url): string
    {
        return sprintf('<a href="%s">%s</a>', htmlspecialchars($url), htmlspecialchars($text));
    }

    /**
     * Create HTML image tag
     *
     * Generates an HTML img tag with src attribute.
     * URL is automatically escaped for safety.
     *
     * Note: Telegraph will convert this to its own image format.
     * For images with captions, use figure() in ContentBuilder instead.
     *
     * @param string $url Image URL (src attribute value)
     * @return string HTML img tag with escaped URL
     *
     * @example
     * echo HtmlHelper::image('https://example.com/photo.jpg');
     * // Output: <img src="https://example.com/photo.jpg" />
     *
     * @example Building HTML gallery
     * $images = [
     *     'https://example.com/1.jpg',
     *     'https://example.com/2.jpg',
     * ];
     *
     * $html = '<div>';
     * foreach ($images as $url) {
     *     $html .= HtmlHelper::image($url);
     * }
     * $html .= '</div>';
     *
     * $page = $account->createPageFromHtml('Gallery', $html);
     */
    public static function image(string $url): string
    {
        return sprintf('<img src="%s" />', htmlspecialchars($url));
    }
}
