<?php

declare(strict_types=1);

namespace Telegraph\Content;

/**
 * Inline Content Builder
 *
 * Builder for creating inline formatted content within block elements
 * (paragraphs, headers, blockquotes, etc.). Used in callable parameters
 * of ContentBuilder methods.
 *
 * Supports text formatting, links, code, and line breaks within a single
 * block element. All methods return self for fluent chaining.
 *
 * @see https://telegra.ph/api
 */
class InlineBuilder
{
    /** @var array Array of inline Telegraph Nodes */
    private array $children = [];

    /**
     * Add plain text
     *
     * Adds unformatted text string to the inline content.
     *
     * @param string $text Text content to add
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->text('Plain text ')->strong('bold')->text(' more text');
     */
    public function text(string $text): self
    {
        $this->children[] = $text;
        return $this;
    }

    /**
     * Add bold text (b tag)
     *
     * Creates bold text using <b> tag.
     * Use this for stylistic bold without semantic meaning.
     * For semantic emphasis, use strong() instead.
     *
     * @param string $text Text to make bold
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->text('This is ')->b('bold')->text(' text');
     */
    public function b(string $text): self
    {
        $this->children[] = ['tag' => 'b', 'children' => [$text]];
        return $this;
    }

    /**
     * Add bold text with strong emphasis (strong tag)
     *
     * Creates bold text using <strong> tag.
     * Use for text with semantic importance/emphasis.
     * Visually identical to b() but semantically different.
     *
     * @param string $text Text to emphasize strongly
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->text('Warning: ')->strong('Important notice!');
     */
    public function strong(string $text): self
    {
        $this->children[] = ['tag' => 'strong', 'children' => [$text]];
        return $this;
    }

    /**
     * Add italic text (i tag)
     *
     * Creates italic text using <i> tag.
     * Use for stylistic italic (foreign words, technical terms).
     * For semantic emphasis, use em() instead.
     *
     * @param string $text Text to italicize
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->text('The term ')->i('carpe diem')->text(' means...');
     */
    public function i(string $text): self
    {
        $this->children[] = ['tag' => 'i', 'children' => [$text]];
        return $this;
    }

    /**
     * Add emphasized text (em tag)
     *
     * Creates italic text using <em> tag.
     * Use for text with semantic emphasis.
     * Visually identical to i() but semantically different.
     *
     * @param string $text Text to emphasize
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->text('You ')->em('must')->text(' do this');
     */
    public function em(string $text): self
    {
        $this->children[] = ['tag' => 'em', 'children' => [$text]];
        return $this;
    }

    /**
     * Add underlined text
     *
     * Creates underlined text using <u> tag.
     *
     * @param string $text Text to underline
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->u('Important')->text(' information');
     */
    public function u(string $text): self
    {
        $this->children[] = ['tag' => 'u', 'children' => [$text]];
        return $this;
    }

    /**
     * Add strikethrough text
     *
     * Creates text with strikethrough using <s> tag.
     * Useful for showing deletions or corrections.
     *
     * @param string $text Text to strike through
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->text('Price: ')->s('$100')->text(' $80');
     */
    public function s(string $text): self
    {
        $this->children[] = ['tag' => 's', 'children' => [$text]];
        return $this;
    }

    /**
     * Add inline code
     *
     * Creates inline code using <code> tag.
     * Use for variable names, function names, or short code snippets
     * within text. For code blocks, use ContentBuilder::pre() instead.
     *
     * @param string $text Code text
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->text('Use the ')->code('$variable')->text(' here');
     */
    public function code(string $text): self
    {
        $this->children[] = ['tag' => 'code', 'children' => [$text]];
        return $this;
    }

    /**
     * Add inline link
     *
     * Creates hyperlink using <a> tag with href attribute.
     *
     * @param string $text Link text (visible to user)
     * @param string $href Link URL
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->text('Visit ')
     *         ->a('our site', 'https://example.com')
     *         ->text(' for details');
     */
    public function a(string $text, string $href): self
    {
        $this->children[] = ['tag' => 'a', 'attrs' => ['href' => $href], 'children' => [$text]];
        return $this;
    }

    /**
     * Add inline link (alias)
     *
     * Alias for a() method. Creates hyperlink with more intuitive name.
     *
     * @param string $text Link text
     * @param string $href Link URL
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->text('Check ')
     *         ->link('this article', 'https://example.com');
     */
    public function link(string $text, string $href): self
    {
        return $this->a($text, $href);
    }

    /**
     * Add line break
     *
     * Inserts <br> tag for line break within inline content.
     * Use this for breaking lines within a paragraph.
     *
     * @return self Returns self for method chaining
     *
     * @example
     * $builder->text('First line')
     *         ->br()
     *         ->text('Second line');
     *
     * @example Address formatting
     * $builder->text('John Doe')
     *         ->br()
     *         ->text('123 Main St')
     *         ->br()
     *         ->text('City, State');
     */
    public function br(): self
    {
        $this->children[] = ['tag' => 'br'];
        return $this;
    }

    /**
     * Build and return inline content array
     *
     * Finalizes building and returns array of inline Telegraph Nodes.
     * This is automatically called by ContentBuilder when using callables.
     *
     * @return array Array of inline Telegraph Nodes
     *
     * @internal This method is typically called by ContentBuilder, not manually
     */
    public function build(): array
    {
        return $this->children;
    }
}
