<?php

declare(strict_types=1);

namespace Telegraph;

use Telegraph\Content\HtmlConverter;
use Telegraph\DTO\AccountData;
use Telegraph\DTO\PageData;

/**
 * Telegraph Account
 *
 * Represents a Telegraph account and provides methods for managing
 * account settings, creating pages, and retrieving account information.
 *
 * @see https://telegra.ph/api
 */
class Account
{
    private ?AccountData $data = null;

    /**
     * Create Account instance
     *
     * @param TelegraphClient $client Telegraph client for API requests
     * @param string|null $accessToken Account access token
     * @param AccountData|null $data Pre-loaded account data
     */
    public function __construct(
        private readonly TelegraphClient $client,
        private readonly ?string $accessToken = null,
        ?AccountData $data = null,
    ) {
        $this->data = $data;
    }

    /**
     * Get account information
     *
     * Fetches account information from Telegraph API and updates internal state.
     * Returns self for method chaining.
     *
     * @param array $fields Fields to retrieve. Available: short_name, author_name, author_url, auth_url, page_count
     * @return self Returns self for method chaining
     * @throws \Telegraph\Exceptions\ApiException If API request fails
     *
     * @example
     * $account->getInfo(['short_name', 'page_count']);
     * echo $account->pageCount();
     */
    public function getInfo(array $fields = ['short_name', 'author_name', 'author_url']): self
    {
        $response = $this->client->request('getAccountInfo', [
            'access_token' => $this->accessToken(),
            'fields' => json_encode($fields),
        ]);

        $this->data = AccountData::fromArray($response);
        return $this;
    }

    /**
     * Edit account information
     *
     * Updates account settings. Pass only the parameters you want to change.
     * Returns self for method chaining.
     *
     * @param string|null $shortName New account name (1-32 characters)
     * @param string|null $authorName New default author name (0-128 characters)
     * @param string|null $authorUrl New default author URL (0-512 characters)
     * @return self Returns self for method chaining
     * @throws \Telegraph\Exceptions\ApiException If API request fails
     *
     * @example
     * $account->edit(shortName: 'NewBlogName', authorName: 'John Doe');
     */
    public function edit(
        ?string $shortName = null,
        ?string $authorName = null,
        ?string $authorUrl = null,
    ): self {
        $response = $this->client->request('editAccountInfo', [
            'access_token' => $this->accessToken(),
            'short_name' => $shortName,
            'author_name' => $authorName,
            'author_url' => $authorUrl,
        ]);

        $this->data = AccountData::fromArray($response);
        return $this;
    }

    /**
     * Create a new Telegraph page
     *
     * Creates a new page on Telegraph under this account.
     * Content can be a string (auto-wrapped in paragraph) or Node array.
     *
     * @param string $title Page title (1-256 characters)
     * @param array|string $content Page content as Node array or plain text
     * @param string|null $authorName Author name displayed below title (0-128 characters)
     * @param string|null $authorUrl Author profile link (0-512 characters)
     * @param bool $returnContent Whether to return page content in response
     * @return Page Created page instance
     * @throws \Telegraph\Exceptions\ApiException If API request fails
     *
     * @example
     * $page = $account->createPage(
     *     title: 'My Article',
     *     content: $telegraph->content()->h3('Title')->p('Text')->build()
     * );
     */
    public function createPage(
        string $title,
        array|string $content,
        ?string $authorName = null,
        ?string $authorUrl = null,
        bool $returnContent = false,
    ): Page {
        if (is_string($content)) {
            $content = $this->client->content()->p($content)->build();
        }

        $response = $this->client->request('createPage', [
            'access_token' => $this->accessToken(),
            'title' => $title,
            'content' => json_encode($content),
            'author_name' => $authorName,
            'author_url' => $authorUrl,
            'return_content' => $returnContent,
        ]);

        return new Page(
            client: $this->client,
            data: PageData::fromArray($response),
            accessToken: $this->accessToken(),
        );
    }

    /**
     * Create a page from HTML
     *
     * Converts HTML to Telegraph format and creates a new page.
     * Automatically filters unsupported tags/attributes and sanitizes URLs.
     *
     * @param string $title Page title (1-256 characters)
     * @param string $html HTML content to convert and publish
     * @param string|null $authorName Author name (0-128 characters)
     * @param string|null $authorUrl Author URL (0-512 characters)
     * @param bool $returnContent Whether to return page content in response
     * @return Page Created page instance
     * @throws \Telegraph\Exceptions\ApiException If API request fails
     *
     * @example
     * $page = $account->createPageFromHtml(
     *     title: 'From HTML',
     *     html: '<h1>Title</h1><p>Text with <strong>bold</strong></p>'
     * );
     */
    public function createPageFromHtml(
        string $title,
        string $html,
        ?string $authorName = null,
        ?string $authorUrl = null,
        bool $returnContent = false,
    ): Page {
        $content = (new HtmlConverter())->convert($html);

        return $this->createPage(
            title: $title,
            content: $content,
            authorName: $authorName,
            authorUrl: $authorUrl,
            returnContent: $returnContent,
        );
    }

    /**
     * Get list of pages belonging to this account
     *
     * Returns a PageList with pages sorted by creation date (newest first).
     * Supports pagination via offset and limit parameters.
     *
     * @param int $offset Sequential number of first page to return (default: 0)
     * @param int $limit Number of pages to retrieve, 0-200 (default: 50)
     * @return PageList List of pages with iteration support
     * @throws \Telegraph\Exceptions\ApiException If API request fails
     *
     * @example
     * $pages = $account->pages(offset: 0, limit: 10);
     * foreach ($pages as $page) {
     *     echo $page->title();
     * }
     */
    public function pages(int $offset = 0, int $limit = 50): PageList
    {
        $response = $this->client->request('getPageList', [
            'access_token' => $this->accessToken(),
            'offset' => $offset,
            'limit' => $limit,
        ]);

        return new PageList(
            client: $this->client,
            data: $response,
            accessToken: $this->accessToken(),
        );
    }

    /**
     * Revoke current access token and generate new one
     *
     * Use this to reset all connected sessions or if you believe
     * the token was compromised. Returns new token via accessToken() method.
     *
     * @return self Returns self with new access token
     * @throws \Telegraph\Exceptions\ApiException If API request fails
     *
     * @example
     * $account->revokeToken();
     * $newToken = $account->accessToken();
     * // Save new token!
     */
    public function revokeToken(): self
    {
        $response = $this->client->request('revokeAccessToken', [
            'access_token' => $this->accessToken(),
        ]);

        $this->data = AccountData::fromArray($response);
        return $this;
    }

    /**
     * Get account short name
     *
     * Returns the account name displayed in the editor.
     * Only visible to account owner.
     *
     * @return string|null Account short name or null if not loaded
     */
    public function shortName(): ?string
    {
        return $this->data?->shortName;
    }

    /**
     * Get default author name
     *
     * Returns the default author name used when creating new articles.
     *
     * @return string|null Author name or null if not set
     */
    public function authorName(): ?string
    {
        return $this->data?->authorName;
    }

    /**
     * Get default author URL
     *
     * Returns the default profile link opened when users click author name.
     *
     * @return string|null Author URL or null if not set
     */
    public function authorUrl(): ?string
    {
        return $this->data?->authorUrl;
    }

    /**
     * Get total page count
     *
     * Returns number of pages belonging to this account.
     * Only available after getInfo() with 'page_count' field.
     *
     * @return int|null Number of pages or null if not loaded
     */
    public function pageCount(): ?int
    {
        return $this->data?->pageCount;
    }

    /**
     * Get account access token
     *
     * Returns access token for this account. Priority: token from data
     * (after createAccount/revokeToken), then token from constructor.
     *
     * @return string|null Access token or null if not available
     */
    public function accessToken(): ?string
    {
        return $this->data?->accessToken ?? $this->accessToken;
    }

    /**
     * Get authorization URL
     *
     * Returns URL to authorize browser on telegra.ph.
     * Valid for single use and 5 minutes only.
     * Only available after createAccount() or revokeToken().
     *
     * @return string|null Authorization URL or null if not available
     */
    public function authUrl(): ?string
    {
        return $this->data?->authUrl;
    }
}
