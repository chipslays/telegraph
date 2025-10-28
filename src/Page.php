<?php

declare(strict_types=1);

namespace Telegraph;

use RuntimeException;
use Telegraph\Content\HtmlConverter;
use Telegraph\DTO\PageData;
use Telegraph\DTO\PageViewsData;

/**
 * Telegraph Page
 *
 * Represents a Telegraph page and provides methods for viewing,
 * editing, and retrieving statistics.
 *
 * @see https://telegra.ph/api
 */
class Page
{
    private ?PageData $data = null;

    /**
     * Create Page instance
     *
     * @param Telegraph $client Telegraph client for API requests
     * @param string|null $path Page path (e.g., 'My-Article-10-28')
     * @param string|null $accessToken Access token for editing operations
     * @param PageData|null $data Pre-loaded page data
     */
    public function __construct(
        private readonly Telegraph $client,
        private readonly ?string $path = null,
        private readonly ?string $accessToken = null,
        ?PageData $data = null,
    ) {
        $this->data = $data;
    }

    /**
     * Get page data from Telegraph
     *
     * Fetches page information from Telegraph API and updates internal state.
     * Returns self for method chaining.
     *
     * @param bool $returnContent Whether to include page content in response (default: false)
     * @return self Returns self for method chaining
     * @throws \Telegraph\Exceptions\ApiException If API request fails
     *
     * @example
     * $page = $telegraph->page('Sample-Page-12-15');
     * $page->get();
     * echo $page->title();
     * echo $page->viewsCount();
     */
    public function get(bool $returnContent = false): self
    {
        $response = $this->client->request('getPage', [
            'path' => $this->getPath(),
            'return_content' => $returnContent,
        ]);

        $this->data = PageData::fromArray($response);
        return $this;
    }

    /**
     * Get page with content
     *
     * Shortcut method to fetch page including content.
     * Equivalent to get(returnContent: true).
     *
     * @return self Returns self for method chaining
     * @throws \Telegraph\Exceptions\ApiException If API request fails
     *
     * @example
     * $page->getWithContent();
     * $content = $page->content();
     * print_r($content);
     */
    public function getWithContent(): self
    {
        return $this->get(true);
    }

    /**
     * Edit Telegraph page
     *
     * Updates existing Telegraph page with new title and content.
     * Requires access token and page ownership.
     * Content can be string (auto-wrapped) or Node array.
     *
     * @param string $title New page title (1-256 characters)
     * @param array|string $content Page content as Node array or plain text
     * @param string|null $authorName Author name displayed below title (0-128 characters)
     * @param string|null $authorUrl Author profile link (0-512 characters)
     * @param bool $returnContent Whether to return updated content in response
     * @return self Returns self for method chaining
     * @throws \Telegraph\Exceptions\ApiException If access denied or request fails
     *
     * @example
     * $page->edit(
     *     title: 'Updated Title',
     *     content: $telegraph->content()->h3('New')->p('Text')->build()
     * );
     */
    public function edit(
        string $title,
        array|string $content,
        ?string $authorName = null,
        ?string $authorUrl = null,
        bool $returnContent = false,
    ): self {
        if (is_string($content)) {
            $content = $this->client->content()->p($content)->build();
        }

        $response = $this->client->request('editPage', [
            'access_token' => $this->accessToken,
            'path' => $this->getPath(),
            'title' => $title,
            'content' => json_encode($content),
            'author_name' => $authorName,
            'author_url' => $authorUrl,
            'return_content' => $returnContent,
        ]);

        $this->data = PageData::fromArray($response);
        return $this;
    }

    /**
     * Edit page from HTML
     *
     * Converts HTML to Telegraph format and updates the page.
     * Automatically filters unsupported tags/attributes.
     *
     * @param string $title New page title (1-256 characters)
     * @param string $html HTML content to convert and update
     * @param string|null $authorName Author name (0-128 characters)
     * @param string|null $authorUrl Author URL (0-512 characters)
     * @param bool $returnContent Whether to return updated content
     * @return self Returns self for method chaining
     * @throws \Telegraph\Exceptions\ApiException If access denied or request fails
     *
     * @example
     * $page->editFromHtml(
     *     title: 'Updated',
     *     html: '<h3>Title</h3><p>New <strong>content</strong></p>'
     * );
     */
    public function editFromHtml(
        string $title,
        string $html,
        ?string $authorName = null,
        ?string $authorUrl = null,
        bool $returnContent = false,
    ): self {
        $content = (new HtmlConverter())->convert($html);

        return $this->edit(
            title: $title,
            content: $content,
            authorName: $authorName,
            authorUrl: $authorUrl,
            returnContent: $returnContent,
        );
    }

    /**
     * Get page view statistics
     *
     * Returns view count for the specified time period.
     * Without parameters, returns total views.
     * Parameters cascade: year -> month -> day -> hour.
     *
     * @param int|null $year Year (2000-2100). Required if month is passed
     * @param int|null $month Month (1-12). Required if day is passed
     * @param int|null $day Day (1-31). Required if hour is passed
     * @param int|null $hour Hour (0-24). Optional
     * @return PageViewsData Object containing view count
     * @throws \Telegraph\Exceptions\ApiException If API request fails
     *
     * @example
     * // Total views
     * $views = $page->views();
     * echo "Total: {$views->views}";
     *
     * // Views in October 2025
     * $views = $page->views(year: 2025, month: 10);
     * echo "October: {$views->views}";
     *
     * // Views on specific day
     * $views = $page->views(year: 2025, month: 10, day: 28);
     */
    public function views(
        ?int $year = null,
        ?int $month = null,
        ?int $day = null,
        ?int $hour = null,
    ): PageViewsData {
        $response = $this->client->request('getViews', [
            'path' => $this->getPath(),
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
        ]);

        return PageViewsData::fromArray($response);
    }

    /**
     * Get page path internally
     *
     * Returns page path from data or constructor.
     * Throws exception if path is not available.
     *
     * @return string Page path
     * @throws RuntimeException If path is not set
     */
    private function getPath(): string
    {
        return $this->data?->path ?? $this->path ?? throw new RuntimeException('Path is not set');
    }

    /**
     * Get page path
     *
     * Returns the page path (everything after https://telegra.ph/).
     * Example: 'My-Article-10-28'
     *
     * @return string|null Page path or null if not available
     */
    public function path(): ?string
    {
        return $this->data?->path ?? $this->path;
    }

    /**
     * Get page URL
     *
     * Returns the full Telegraph page URL.
     * Example: 'https://telegra.ph/My-Article-10-28'
     *
     * @return string|null Full page URL or null if not loaded
     */
    public function url(): ?string
    {
        return $this->data?->url;
    }

    /**
     * Get page title
     *
     * Returns the page title as displayed at the top of the article.
     *
     * @return string|null Page title or null if not loaded
     */
    public function title(): ?string
    {
        return $this->data?->title;
    }

    /**
     * Get page description
     *
     * Returns auto-generated page description (excerpt from content).
     *
     * @return string|null Page description or null if not available
     */
    public function description(): ?string
    {
        return $this->data?->description;
    }

    /**
     * Get page content
     *
     * Returns page content as Telegraph Node array.
     * Only available after get(returnContent: true) or getWithContent().
     *
     * @return array|null Node array or null if content not loaded
     *
     * @example
     * $page->getWithContent();
     * $content = $page->content();
     * foreach ($content as $node) {
     *     // Process nodes
     * }
     */
    public function content(): ?array
    {
        return $this->data?->content;
    }

    /**
     * Get total view count
     *
     * Returns number of page views. Updated when page is loaded.
     *
     * @return int|null View count or null if not loaded
     */
    public function viewsCount(): ?int
    {
        return $this->data?->views;
    }

    /**
     * Check if page can be edited
     *
     * Returns true if current access token can edit this page.
     * Only available when access token was passed to get() request.
     *
     * @return bool|null True if editable, false if not, null if unknown
     *
     * @example
     * $page->get();
     * if ($page->canEdit()) {
     *     $page->edit('New Title', 'New content');
     * }
     */
    public function canEdit(): ?bool
    {
        return $this->data?->canEdit;
    }
}
