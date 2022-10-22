<?php

namespace Telegraph\Types;

use Telegraph\Exceptions\BadTypeParameterException;
use JsonSerializable;

class NodeElement implements JsonSerializable
{
    protected $availableTags = [
        'a',
        'aside',
        'b',
        'blockquote',
        'br',
        'code',
        'em',
        'figcaption',
        'figure',
        'h3',
        'h4',
        'hr',
        'i',
        'iframe',
        'img',
        'li',
        'ol',
        'p',
        'pre',
        's',
        'strong',
        'u',
        'ul',
        'video',
    ];

    public function __construct(
        protected string $tag,
        protected string|array $children = [],
        protected array $attrs = [],
        bool $forceTag = false
    ) {
        if (!$forceTag && !in_array($this->tag, $this->availableTags)) {
            throw new BadTypeParameterException(
                "Tag '{$this->tag}' not avaiable in tags list: " . implode(', ', $this->availableTags)
            );
        }

        $this->tag = $tag;
        $this->children = (array) $children;
        $this->attrs = $attrs;
    }

    /**
     * @param string $tag
     * @return self
     */
    public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @param array $attrs
     * @return self
     */
    public function setAttrs(array $attrs): self
    {
        $this->attrs = $attrs;

        return $this;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return self
     */
    public function addAttr(string $attribute, mixed $value): self
    {
        $this->attrs[$attribute] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttrs(): array
    {
        return $this->attrs;
    }

    /**
     * @param array $children
     * @return self
     */
    public function setChildren(array $children): self
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param array $value
     * @return self
     */
    public function addChildren(array $value): self
    {
        $this->children[] = $value;

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasChildren(): bool
    {
        return (bool) count($this->children);
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            'tag' => $this->tag,
            'attrs' => $this->attrs,
            'children' => $this->children,
        ];
    }
}
