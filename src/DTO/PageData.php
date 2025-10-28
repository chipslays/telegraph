<?php

declare(strict_types=1);

namespace Telegraph\DTO;

/**
 * Telegraph Page Data Transfer Object
 *
 * Immutable data object representing Telegraph page information
 * returned by API methods (createPage, editPage, getPage, getPageList).
 *
 * All properties are readonly and set via constructor.
 * Use fromArray() factory method to create from API response.
 *
 * @see https://telegra.ph/api Page object documentation
 */
readonly class PageData
{
    /**
     * Create PageData instance
     *
     * @param string $path Page path (e.g., 'Sample-Page-12-15')
     * @param string $url Full page URL (e.g., 'https://telegra.ph/Sample-Page-12-15')
     * @param string $title Page title (1-256 chars)
     * @param string|null $description Auto-generated page description/excerpt
     * @param string|null $authorName Author name displayed below title (0-128 chars)
     * @param string|null $authorUrl Author profile link (0-512 chars)
     * @param string|null $imageUrl URL of page's primary image
     * @param array|null $content Page content as Telegraph Node array (only when returnContent=true)
     * @param int|null $views Total number of page views
     * @param bool|null $canEdit Whether current access token can edit this page (only when token provided)
     */
    public function __construct(
        public string $path,
        public string $url,
        public string $title,
        public ?string $description = null,
        public ?string $authorName = null,
        public ?string $authorUrl = null,
        public ?string $imageUrl = null,
        public ?array $content = null,
        public ?int $views = null,
        public ?bool $canEdit = null,
    ) {}

    /**
     * Create PageData from API response array
     *
     * Factory method that constructs PageData from Telegraph API response.
     * Handles optional fields gracefully with null coalescing.
     *
     * @param array $data API response array with snake_case keys
     * @return self New PageData instance
     *
     * @example
     * $response = [
     *     'path' => 'Sample-Page-12-15',
     *     'url' => 'https://telegra.ph/Sample-Page-12-15',
     *     'title' => 'My Article'
     * ];
     * $pageData = PageData::fromArray($response);
     */
    public static function fromArray(array $data): self
    {
        return new self(
            path: $data['path'],
            url: $data['url'],
            title: $data['title'],
            description: $data['description'] ?? null,
            authorName: $data['author_name'] ?? null,
            authorUrl: $data['author_url'] ?? null,
            imageUrl: $data['image_url'] ?? null,
            content: $data['content'] ?? null,
            views: $data['views'] ?? null,
            canEdit: $data['can_edit'] ?? null,
        );
    }
}
