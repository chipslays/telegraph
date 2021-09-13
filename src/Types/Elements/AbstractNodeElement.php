<?php

namespace Chipslays\Telegraph\Types\Elements;

abstract class AbstractNodeElement
{
    /**
     * Name of the DOM element.
     * Available tags: a, aside, b, blockquote, br, code, em,
     *                 figcaption, figure, h3, h4, hr, i,
     *                 iframe, img, li, ol, p, pre, s, strong,
     *                 u, ul, video.
     *
     * @var string
     */
    protected $tag;

    /**
     * Attributes of the DOM element.
     * Key of object represents name of attribute, value represents value of attribute.
     * Available attributes: href, src.
     *
     * @var array
     */
    protected $attrs;

    /**
     * List of child nodes for the DOM element.
     *
     * @var array
     */
    protected $children;

    public function __construct(string $tag, array $children = [], array $attrs = [])
    {
        $this->tag = $tag;
        $this->attrs = $attrs;
        $this->children = $children;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @return array
     */
    public function getAttrs(): array
    {
        return $this->attrs;
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param string $tag
     * @return self
     */
    public function setTag(string $tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @param array
     *
     * @return self
     */
    public function setAttrs(array $attrs)
    {
        $this->attrs = $attrs;

        return $this;
    }

    /**
     * @param array
     *
     * @return self
     */
    public function setChildren(array $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return (bool) count($this->children);
    }

    /**
     * @param string|AbstractNodeElement $value
     */
    public function addChildren($value)
    {
        $this->children[] = $value;
    }
    /**
     * @param string|AbstractNodeElement $value
     */
    public function addAttr($attribute, $value)
    {
        $this->attrs[$attribute] = $value;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'tag' => $this->getTag(),
            'attrs' => $this->getAttrs(),
            'children' => $this->getChildren(),
        ];
    }
}
