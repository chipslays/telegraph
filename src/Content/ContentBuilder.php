<?php

declare(strict_types=1);

namespace Telegraph\Content;

use Telegraph\Enums\Tag;

/**
 * Telegraph Content Builder
 *
 * Fluent interface builder for creating Telegraph page content.
 * Supports method chaining and various content types including
 * text, headers, lists, images, and inline formatted content.
 *
 * @see https://telegra.ph/api
 */
class ContentBuilder
{
    /** @var array Array of Telegraph Node elements */
    private array $nodes = [];

    /**
     * Add a node to the content
     *
     * Low-level method to add any node (string, array, or NodeBuilder).
     * Most users should use specific methods (p(), h3(), etc.) instead.
     *
     * @param array|string|NodeBuilder $node Node to add
     * @return self Returns self for method chaining
     */
    public function add(array|string|NodeBuilder $node): self
    {
        if ($node instanceof NodeBuilder) {
            $node = $node->build();
        }

        $this->nodes[] = $node;
        return $this;
    }

    /**
     * Add a paragraph
     *
     * Creates a paragraph element with content.
     * Supports three content types:
     * - String: plain text
     * - Array: pre-built Node array
     * - Callable: receives InlineBuilder for formatted content
     *
     * @param string|array|callable $content Paragraph content
     * @return self Returns self for method chaining
     *
     * @example
     * // Simple text
     * $builder->p('Plain text paragraph');
     *
     * // With inline formatting
     * $builder->p(function($b) {
     *     $b->text('Text with ')
     *       ->strong('bold')
     *       ->text(' and ')
     *       ->em('italic');
     * });
     */
    public function p(string|array|callable $content): self
    {
        if (is_callable($content)) {
            $builder = new InlineBuilder();
            $content($builder);
            $children = $builder->build();
        } elseif (is_string($content)) {
            $children = [$content];
        } else {
            $children = $content;
        }

        return $this->add(['tag' => 'p', 'children' => $children]);
    }

    /**
     * Add a level 3 header
     *
     * Creates h3 heading element. Supports string, array, or callable content.
     *
     * @param string|array|callable $content Header content
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->h3('Section Title');
     */
    public function h3(string|array|callable $content): self
    {
        if (is_callable($content)) {
            $builder = new InlineBuilder();
            $content($builder);
            $children = $builder->build();
        } elseif (is_string($content)) {
            $children = [$content];
        } else {
            $children = $content;
        }

        return $this->add(['tag' => 'h3', 'children' => $children]);
    }

    /**
     * Add a level 4 header
     *
     * Creates h4 heading element (subheading).
     * Supports string, array, or callable content.
     *
     * @param string|array|callable $content Header content
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->h4('Subsection Title');
     */
    public function h4(string|array|callable $content): self
    {
        if (is_callable($content)) {
            $builder = new InlineBuilder();
            $content($builder);
            $children = $builder->build();
        } elseif (is_string($content)) {
            $children = [$content];
        } else {
            $children = $content;
        }

        return $this->add(['tag' => 'h4', 'children' => $children]);
    }

    /**
     * Add a blockquote
     *
     * Creates a blockquote element for quotes or highlighted text.
     * Supports string, array, or callable content.
     *
     * @param string|array|callable $content Quote content
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->blockquote('This is an important quote.');
     */
    public function blockquote(string|array|callable $content): self
    {
        if (is_callable($content)) {
            $builder = new InlineBuilder();
            $content($builder);
            $children = $builder->build();
        } elseif (is_string($content)) {
            $children = [$content];
        } else {
            $children = $content;
        }

        return $this->add(['tag' => 'blockquote', 'children' => $children]);
    }

    /**
     * Add preformatted code block
     *
     * Creates a pre element for displaying code or preformatted text.
     * Preserves whitespace and line breaks.
     *
     * @param string $content Code or preformatted text
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->pre('<?php
     * echo "Hello World";
     * ?>');
     */
    public function pre(string $content): self
    {
        return $this->add(['tag' => 'pre', 'children' => [$content]]);
    }

    /**
     * Add an aside element
     *
     * Creates an aside element for tangentially related content.
     * Supports string, array, or callable content.
     *
     * @param string|array|callable $content Aside content
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->aside('Note: This is additional information.');
     */
    public function aside(string|array|callable $content): self
    {
        if (is_callable($content)) {
            $builder = new InlineBuilder();
            $content($builder);
            $children = $builder->build();
        } elseif (is_string($content)) {
            $children = [$content];
        } else {
            $children = $content;
        }

        return $this->add(['tag' => 'aside', 'children' => $children]);
    }

    /**
     * Add an image
     *
     * Creates an img element with the specified source URL.
     *
     * @param string $src Image URL (must be publicly accessible)
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->img('https://example.com/image.jpg');
     */
    public function img(string $src): self
    {
        return $this->add(['tag' => 'img', 'attrs' => ['src' => $src]]);
    }

    /**
     * Add a video
     *
     * Creates a video element with the specified source URL.
     *
     * @param string $src Video URL
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->video('https://example.com/video.mp4');
     */
    public function video(string $src): self
    {
        return $this->add(['tag' => 'video', 'attrs' => ['src' => $src]]);
    }

    /**
     * Add an iframe
     *
     * Creates an iframe element for embedding external content.
     * Common use: YouTube videos, maps, etc.
     *
     * @param string $src Iframe source URL
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->iframe('https://www.youtube.com/embed/VIDEO_ID');
     */
    public function iframe(string $src): self
    {
        return $this->add(['tag' => 'iframe', 'attrs' => ['src' => $src]]);
    }

    /**
     * Add embed iframe using Telegraph proxy
     *
     * Creates figure with iframe using Telegraph's /embed/ endpoint.
     * Supports youtube, vimeo, twitter and other vendors.
     *
     * @param string $vendor Vendor name (youtube, vimeo, twitter, etc.)
     * @param string $url Original URL to embed
     * @param string|null $caption Optional caption
     * @return self Returns self for method chaining
     */
    public function embed(string $vendor, string $url, ?string $caption = null): self
    {
        $children = [
            ['tag' => 'iframe', 'attrs' => ['src' => '/embed/' . $vendor . '?url=' . rawurlencode($url)]]
        ];

        if ($caption) {
            $children[] = ['tag' => 'figcaption', 'children' => [$caption]];
        }

        return $this->add(['tag' => 'figure', 'children' => $children]);
    }

    /**
     * Add YouTube video embed
     */
    public function youtube(string $url, ?string $caption = null): self
    {
        return $this->embed('youtube', $url, $caption);
    }

    /**
     * Add Vimeo video embed
     */
    public function vimeo(string $url, ?string $caption = null): self
    {
        return $this->embed('vimeo', $url, $caption);
    }

    /**
     * Add Twitter post embed
     */
    public function twitter(string $url, ?string $caption = null): self
    {
        return $this->embed('twitter', $url, $caption);
    }



    /**
     * Add a line break
     *
     * Creates a br element for inserting line breaks between blocks.
     * For inline breaks, use InlineBuilder's br() method inside callbacks.
     *
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->p('First paragraph')
     *         ->br()
     *         ->p('Second paragraph with spacing');
     */
    public function br(): self
    {
        return $this->add(['tag' => 'br']);
    }

    /**
     * Add a horizontal rule
     *
     * Creates an hr element for visual separation between sections.
     *
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->p('Section one')
     *         ->hr()
     *         ->p('Section two');
     */
    public function hr(): self
    {
        return $this->add(['tag' => 'hr']);
    }

    /**
     * Add an unordered list
     *
     * Creates a ul element with li items.
     * Each item can be a string or Node array.
     *
     * @param array $items Array of list items (strings or Node arrays)
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->ul([
     *     'First item',
     *     'Second item',
     *     'Third item'
     * ]);
     */
    public function ul(array $items): self
    {
        $children = array_map(function($item) {
            if (is_string($item)) {
                return ['tag' => 'li', 'children' => [$item]];
            }
            return ['tag' => 'li', 'children' => $item];
        }, $items);

        return $this->add(['tag' => 'ul', 'children' => $children]);
    }

    /**
     * Add an ordered list
     *
     * Creates an ol element with numbered li items.
     * Each item can be a string or Node array.
     *
     * @param array $items Array of list items (strings or Node arrays)
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->ol([
     *     'First step',
     *     'Second step',
     *     'Third step'
     * ]);
     */
    public function ol(array $items): self
    {
        $children = array_map(function($item) {
            if (is_string($item)) {
                return ['tag' => 'li', 'children' => [$item]];
            }
            return ['tag' => 'li', 'children' => $item];
        }, $items);

        return $this->add(['tag' => 'ol', 'children' => $children]);
    }

    /**
     * Add a figure with image and optional caption
     *
     * Creates a figure element containing an image and optional figcaption.
     * Used for images that need captions or descriptions.
     *
     * @param string $imageSrc Image source URL
     * @param string|null $caption Optional caption text
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->figure(
     *     'https://example.com/photo.jpg',
     *     'Beautiful landscape photograph'
     * );
     */
    public function figure(string $imageSrc, ?string $caption = null): self
    {
        $children = [['tag' => 'img', 'attrs' => ['src' => $imageSrc]]];

        if ($caption) {
            $children[] = ['tag' => 'figcaption', 'children' => [$caption]];
        }

        return $this->add(['tag' => 'figure', 'children' => $children]);
    }

    /**
     * Build and return the content array
     *
     * Finalizes the content building and returns the Telegraph Node array.
     * This array is ready to be passed to createPage or editPage methods.
     *
     * @return array Array of Telegraph Nodes
     *
     * @example
     * $content = $telegraph->content()
     *     ->h3('Title')
     *     ->p('Text')
     *     ->build();
     *
     * $page = $account->createPage('My Page', $content);
     */
    public function build(): array
    {
        return $this->nodes;
    }
}
