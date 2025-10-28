<?php

declare(strict_types=1);

namespace Telegraph\Content;

use Telegraph\Enums\Tag;

/**
 * Telegraph Node Builder
 *
 * Low-level builder for creating individual Telegraph Node elements.
 * Provides fine-grained control over node construction with manual
 * specification of tags, attributes, and children.
 *
 * Most users should use ContentBuilder instead. NodeBuilder is useful
 * for advanced cases requiring custom node structures or when
 * integrating with other systems.
 *
 * @see https://telegra.ph/api
 */
class NodeBuilder
{
    /** @var array The Telegraph Node being built */
    private array $node = [];

    /**
     * Create NodeBuilder instance
     *
     * @param ContentBuilder|null $parent Optional parent builder for chaining back with end()
     */
    public function __construct(
        private readonly ?ContentBuilder $parent = null,
    ) {}

    /**
     * Set node tag
     *
     * Sets the HTML tag name for this node.
     * Accepts Tag enum or string. Only Telegraph-supported tags will work.
     *
     * @param Tag|string $tag Tag name (e.g., 'p', 'h3', 'a') or Tag enum
     * @return self Returns self for method chaining
     *
     * @example
     * $node = $builder->node()
     *     ->tag('p')
     *     ->text('Content');
     *
     * @example Using Tag enum
     * $node = $builder->node()
     *     ->tag(Tag::H3)
     *     ->text('Header');
     */
    public function tag(Tag|string $tag): self
    {
        $this->node['tag'] = $tag instanceof Tag ? $tag->value : $tag;
        return $this;
    }

    /**
     * Set href attribute
     *
     * Sets the href attribute for link nodes (<a> tags).
     * Only meaningful for anchor tags, ignored for other elements.
     *
     * @param string $href URL for the link
     * @return self Returns self for method chaining
     *
     * @example
     * $link = $builder->node()
     *     ->tag('a')
     *     ->href('https://example.com')
     *     ->text('Click here');
     */
    public function href(string $href): self
    {
        $this->node['attrs']['href'] = $href;
        return $this;
    }

    /**
     * Set src attribute
     *
     * Sets the src attribute for media nodes (img, video, iframe).
     * Only meaningful for media tags, ignored for other elements.
     *
     * @param string $src URL for the media resource
     * @return self Returns self for method chaining
     *
     * @example
     * $image = $builder->node()
     *     ->tag('img')
     *     ->src('https://example.com/photo.jpg');
     */
    public function src(string $src): self
    {
        $this->node['attrs']['src'] = $src;
        return $this;
    }

    /**
     * Set all children at once
     *
     * Replaces any existing children with the provided array.
     * Children can be strings (text nodes) or arrays (element nodes).
     *
     * @param array $children Array of child nodes
     * @return self Returns self for method chaining
     *
     * @example
     * $node = $builder->node()
     *     ->tag('p')
     *     ->children(['Text', ['tag' => 'strong', 'children' => ['Bold']]]);
     */
    public function children(array $children): self
    {
        $this->node['children'] = $children;
        return $this;
    }

    /**
     * Add a single child node
     *
     * Appends one child to existing children array.
     * Can be called multiple times to add multiple children.
     *
     * @param string|array $child Child node (string for text, array for element)
     * @return self Returns self for method chaining
     *
     * @example
     * $node = $builder->node()
     *     ->tag('p')
     *     ->child('First part ')
     *     ->child(['tag' => 'strong', 'children' => ['bold']])
     *     ->child(' last part');
     */
    public function child(string|array $child): self
    {
        $this->node['children'][] = $child;
        return $this;
    }

    /**
     * Add text child (alias)
     *
     * Convenience alias for child() when adding text.
     * More readable than child() for text-only additions.
     *
     * @param string $text Text content to add
     * @return self Returns self for method chaining
     *
     * @example
     * $node = $builder->node()
     *     ->tag('p')
     *     ->text('Simple text content');
     */
    public function text(string $text): self
    {
        return $this->child($text);
    }

    /**
     * Build and return the node
     *
     * Finalizes building and returns the Telegraph Node array.
     * The node is ready to be added to content or passed to API.
     *
     * @return array Telegraph Node array
     *
     * @example
     * $node = $builder->node()
     *     ->tag('p')
     *     ->text('Content')
     *     ->build();
     * // Returns: ['tag' => 'p', 'children' => ['Content']]
     */
    public function build(): array
    {
        return $this->node;
    }

    /**
     * End building and return to parent
     *
     * Adds this node to parent ContentBuilder and returns the parent.
     * Enables fluent chaining from NodeBuilder back to ContentBuilder.
     * Returns null if there's no parent.
     *
     * @return ContentBuilder|null Parent builder or null if no parent
     *
     * @example
     * $content = $builder
     *     ->node()
     *         ->tag('p')
     *         ->text('Custom node')
     *     ->end()  // Returns to ContentBuilder
     *     ->h3('Next section')
     *     ->build();
     */
    public function end(): ?ContentBuilder
    {
        return $this->parent?->add($this);
    }
}
