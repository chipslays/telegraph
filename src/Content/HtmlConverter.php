<?php

declare(strict_types=1);

namespace Telegraph\Content;

use DOMDocument;
use DOMNode;
use DOMText;
use DOMElement;

/**
 * HTML to Telegraph Converter
 *
 * Converts HTML content to Telegraph Node format with automatic
 * sanitization and filtering. Removes unsupported tags/attributes,
 * sanitizes URLs, and normalizes whitespace.
 *
 * Uses whitelist approach: only explicitly allowed tags and attributes
 * (href, src) are preserved. Everything else is stripped or ignored.
 *
 * @see https://telegra.ph/api
 */
class HtmlConverter
{
    /**
     * Tags supported by Telegraph API
     * Only these tags will be included in output
     * @var string[]
     */
    private const ALLOWED_TAGS = [
        'a', 'aside', 'b', 'blockquote', 'br', 'code', 'em', 'figcaption',
        'figure', 'h3', 'h4', 'hr', 'i', 'iframe', 'img', 'li', 'ol',
        'p', 'pre', 's', 'strong', 'u', 'ul', 'video'
    ];

    /**
     * Tags that support href attribute
     * @var string[]
     */
    private const HREF_TAGS = ['a'];

    /**
     * Tags that support src attribute
     * @var string[]
     */
    private const SRC_TAGS = ['img', 'video', 'iframe'];

    /**
     * Tags to completely ignore including their content
     * Used for dangerous or meaningless elements
     * @var string[]
     */
    private const IGNORE_TAGS = [
        'script', 'style', 'noscript', 'object',
        'embed', 'applet', 'link', 'meta'
    ];

    /**
     * Tags to unwrap (remove tag but keep content)
     * Used for container elements
     * @var string[]
     */
    private const UNWRAP_TAGS = [
        'span', 'div', 'section', 'article',
        'header', 'footer', 'main', 'nav'
    ];

    /**
     * Tag replacements mapping
     * Maps unsupported heading levels to supported ones
     * @var array<string, string>
     */
    private const TAG_REPLACEMENTS = [
        'h1' => 'h3',
        'h2' => 'h3',
        'h5' => 'h4',
        'h6' => 'h4',
    ];

    /**
     * Convert HTML to Telegraph Node format
     *
     * Main public method that converts HTML string to Telegraph-compatible
     * Node array. Automatically:
     * - Filters unsupported tags (whitelist approach)
     * - Removes all attributes except href and src
     * - Sanitizes URLs to prevent XSS
     * - Normalizes whitespace
     * - Converts heading levels (h1/h2 → h3, h5/h6 → h4)
     * - Removes dangerous content (script, style tags)
     *
     * @param string $html HTML content to convert
     * @return array Telegraph Node array ready for API
     *
     * @example
     * $converter = new HtmlConverter();
     * $content = $converter->convert('<h1>Title</h1><p>Text</p>');
     * // Returns: [['tag' => 'h3', 'children' => ['Title']], ['tag' => 'p', 'children' => ['Text']]]
     */
    public function convert(string $html): array
    {
        if (trim($html) === '') {
            return [];
        }

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = true;

        libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="UTF-8">' . $html,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $body = $dom->getElementsByTagName('body')->item(0);
        $container = $body ?? $dom;

        return $this->processNodes($container->childNodes);
    }

    /**
     * Process a list of DOM nodes
     *
     * Iterates through node list and converts each node to Telegraph format.
     * Handles node unwrapping (flattening arrays) when tags are removed.
     *
     * @param \DOMNodeList|\Traversable $nodeList List of DOM nodes to process
     * @return array Array of Telegraph Nodes (strings and arrays)
     */
    private function processNodes($nodeList): array
    {
        $nodes = [];

        foreach ($nodeList as $child) {
            $node = $this->convertNode($child);

            if ($node === null) {
                continue;
            }

            // Flatten unwrapped nodes (arrays without 'tag' key)
            if (is_array($node) && !isset($node['tag'])) {
                $nodes = array_merge($nodes, $node);
            } else {
                $nodes[] = $node;
            }
        }

        return $nodes;
    }

    /**
     * Convert single DOM node to Telegraph format
     *
     * Handles three node types:
     * 1. Text nodes: normalized and returned as strings
     * 2. Element nodes: converted based on tag whitelist/blacklist
     * 3. Other nodes: ignored (comments, etc.)
     *
     * Applies tag replacements, unwrapping, and filtering based on
     * ALLOWED_TAGS, IGNORE_TAGS, and UNWRAP_TAGS constants.
     *
     * @param DOMNode $node DOM node to convert
     * @return string|array|null Telegraph Node (string for text, array for elements, null to skip)
     */
    private function convertNode(DOMNode $node): string|array|null
    {
        if ($node instanceof DOMText) {
            $text = $node->textContent;

            // Normalize whitespace: multiple spaces/newlines become single space
            $text = preg_replace('/\s+/', ' ', $text);

            return trim($text) === '' ? null : $text;
        }

        if (!$node instanceof DOMElement) {
            return null;
        }

        $tagName = strtolower($node->tagName);

        // Completely ignore dangerous/useless tags with content
        if (in_array($tagName, self::IGNORE_TAGS)) {
            return null;
        }

        // Replace unsupported heading levels
        if (isset(self::TAG_REPLACEMENTS[$tagName])) {
            $tagName = self::TAG_REPLACEMENTS[$tagName];
        }
        // Unwrap container tags (keep children, remove tag)
        else if (in_array($tagName, self::UNWRAP_TAGS)) {
            return $this->processNodes($node->childNodes);
        }
        // Ignore all other unsupported tags
        else if (!in_array($tagName, self::ALLOWED_TAGS)) {
            return null;
        }

        $element = ['tag' => $tagName];

        // Extract only whitelisted attributes
        $attrs = $this->extractAllowedAttributes($node, $tagName);
        if (!empty($attrs)) {
            $element['attrs'] = $attrs;
        }

        // Process children for non-void elements
        if (!in_array($tagName, ['br', 'hr', 'img'])) {
            $children = $this->processNodes($node->childNodes);
            if (!empty($children)) {
                $element['children'] = $children;
            }
        }

        return $element;
    }

    /**
     * Extract allowed attributes from element
     *
     * Implements whitelist approach: only href (for links) and src (for media)
     * attributes are extracted. All other attributes (class, id, style, etc.)
     * are completely ignored.
     *
     * URLs are sanitized to prevent XSS attacks.
     *
     * @param DOMElement $node DOM element to extract attributes from
     * @param string $tagName Tag name (used to determine which attributes are allowed)
     * @return array Associative array of allowed attributes (empty if none)
     */
    private function extractAllowedAttributes(DOMElement $node, string $tagName): array
    {
        $attrs = [];

        // Extract href only for <a> tags
        if (in_array($tagName, self::HREF_TAGS) && $node->hasAttribute('href')) {
            $href = $this->sanitizeUrl($node->getAttribute('href'));
            if ($href !== '') {
                $attrs['href'] = $href;
            }
        }

        // Extract src only for media tags
        if (in_array($tagName, self::SRC_TAGS) && $node->hasAttribute('src')) {
            $src = $this->sanitizeUrl($node->getAttribute('src'));
            if ($src !== '') {
                $attrs['src'] = $src;
            }
        }

        return $attrs;
    }

    /**
     * Sanitize URL to prevent security issues
     *
     * Blocks dangerous URL protocols that could lead to XSS or other
     * security vulnerabilities. Returns empty string if URL is dangerous.
     *
     * @param string $url URL to sanitize
     * @return string Sanitized URL or empty string if dangerous
     */
    private function sanitizeUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            return '';
        }

        return $url;
    }
}
