<?php

declare(strict_types=1);

namespace Telegraph;

use Telegraph\Content\ContentBuilder;
use Telegraph\Content\HtmlConverter;
use Telegraph\Exceptions\ApiException;
use Telegraph\DTO\AccountData;

/**
 * Telegraph API Client
 *
 * Main client for interacting with Telegraph API.
 * Provides methods for account management, page creation/editing,
 * and HTML to Telegraph format conversion.
 *
 * @see https://telegra.ph/api
 */
class Telegraph
{
    private const API_URL = 'https://api.telegra.ph';

    /**
     * Create a new Telegraph client instance
     *
     * @param string|null $accessToken Optional access token for authenticated requests
     * @param int $timeout Request timeout in seconds (default: 30)
     */
    public function __construct(
        private readonly ?string $accessToken = null,
        private readonly int $timeout = 30,
    ) {}

    /**
     * Create a new Telegraph account
     *
     * Creates a new Telegraph account with the specified parameters.
     * Returns an Account object with access_token that should be saved.
     *
     * @param string $shortName Account name (1-32 characters), displayed to user in editor
     * @param string|null $authorName Default author name for new articles (0-128 characters)
     * @param string|null $authorUrl Default author profile link (0-512 characters)
     * @return Account Account object with access token
     * @throws ApiException If API request fails or validation errors occur
     *
     * @example
     * $account = $telegraph->createAccount('MyBlog', 'John Doe', 'https://example.com');
     * $token = $account->accessToken(); // Save this!
     */
    public function createAccount(
        string $shortName,
        ?string $authorName = null,
        ?string $authorUrl = null,
    ): Account {
        $response = $this->request('createAccount', [
            'short_name' => $shortName,
            'author_name' => $authorName,
            'author_url' => $authorUrl,
        ]);

        return new Account(
            client: $this,
            data: AccountData::fromArray($response),
        );
    }

    /**
     * Get an existing Telegraph account instance
     *
     * Returns an Account object for working with an existing account.
     * If no access token is provided, uses the token from client constructor.
     *
     * @param string|null $accessToken Account access token. If null, uses client token
     * @return Account Account instance for managing pages and settings
     *
     * @example
     * $account = $telegraph->account('your_access_token_here');
     * $account->getInfo();
     */
    public function account(?string $accessToken = null): Account
    {
        return new Account(
            client: $this,
            accessToken: $accessToken ?? $this->accessToken,
        );
    }

    /**
     * Get a Telegraph page instance by path
     *
     * Returns a Page object for reading or editing an existing Telegraph page.
     * The path is everything after https://telegra.ph/ in the page URL.
     *
     * @param string $path Page path (e.g., 'Sample-Page-12-15')
     * @return Page Page instance for viewing/editing
     *
     * @example
     * $page = $telegraph->page('My-Article-10-28');
     * $page->get();
     * echo $page->title();
     */
    public function page(string $path): Page
    {
        return new Page(
            client: $this,
            path: $path,
            accessToken: $this->accessToken,
        );
    }

    /**
     * Create a new content builder instance
     *
     * Returns a ContentBuilder for constructing Telegraph page content
     * using a fluent interface with method chaining.
     *
     * @return ContentBuilder Builder instance for creating page content
     *
     * @example
     * $content = $telegraph->content()
     *     ->h3('Title')
     *     ->p('Paragraph text')
     *     ->build();
     */
    public function content(): ContentBuilder
    {
        return new ContentBuilder();
    }

    /**
     * Convert HTML to Telegraph content format
     *
     * Converts HTML string to Telegraph Node array format.
     * Automatically filters unsupported tags and attributes,
     * sanitizes URLs, and normalizes the structure.
     *
     * @param string $html HTML content to convert
     * @return array Telegraph Node array ready for API submission
     *
     * @example
     * $content = $telegraph->fromHtml('<h1>Title</h1><p>Text</p>');
     * $page = $account->createPage('Title', $content);
     */
    public function fromHtml(string $html): array
    {
        return (new HtmlConverter())->convert($html);
    }

    /**
     * Get HTML converter instance
     *
     * Returns an HtmlConverter instance for converting HTML to Telegraph format.
     * Use this if you need to reuse the converter multiple times.
     *
     * @return HtmlConverter HTML converter instance
     *
     * @example
     * $converter = $telegraph->html();
     * $content1 = $converter->convert($html1);
     * $content2 = $converter->convert($html2);
     */
    public function html(): HtmlConverter
    {
        return new HtmlConverter();
    }

    /**
     * Make a direct API request to Telegraph
     *
     * Low-level method for making HTTP requests to Telegraph API.
     * Automatically adds access token if available.
     * Throws exception on API errors.
     *
     * @param string $method API method name (e.g., 'createPage', 'getPage')
     * @param array $params Request parameters as associative array
     * @return array API response result array
     * @throws ApiException If request fails or API returns error
     *
     * @internal This method is primarily for internal use
     */
    public function request(string $method, array $params = []): array
    {
        $params = array_filter($params, fn($value) => $value !== null);

        if ($this->accessToken && !isset($params['access_token'])) {
            $params['access_token'] = $this->accessToken;
        }

        $url = self::API_URL . '/' . $method;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new ApiException('Request failed');
        }

        $data = json_decode($response, true);

        if (!$data['ok']) {
            throw new ApiException($data['error'] ?? 'Unknown error');
        }

        return $data['result'];
    }
}
