<?php

namespace Telegraph;

use Telegraph\Types\Page;
use Telegraph\Types\PageList;
use Telegraph\Types\Account;
use Telegraph\Types\NodeElement;
use Telegraph\Traits\WithApiMethod;
use CurlHandle;
use Closure;

class Client
{
    use WithApiMethod;

    /**
     * Constructor.
     *
     * @param CurlHandle|null $httpClient
     */
    public function __construct(?CurlHandle $httpClient = null)
    {
        if (!$httpClient) {
            $this->setupDefaultHttpClient();
        } else {
            $this->setHttpClient($httpClient);
        }
    }

    /**
     * Create a new Telegraph account.
     *
     * @param string $shortName [Required, 1-32 characters] Private name, other users don't see this name.
     * @param string|null $authorName [Optional, 0-128 characters] Public name, visible for other users.
     * @param string|null $authorUrl [Optional, 0-512 characters] Can be any link, not necessarily to a Telegram profile or channel.
     * @return Account
     */
    public function createAccount(
        string $shortName,
        ?string $authorName = null,
        ?string $authorUrl = null,
    ): Account {
        $response = $this->api(__FUNCTION__, [
            'short_name' => $shortName,
            'author_name' => $authorName,
            'author_url' => $authorUrl,
        ]);

        return new Account($response, client: $this);
    }

    /**
     * Update information about a Telegraph account.
     *
     * Pass only the parameters that you want to edit.
     *
     * On success, returns an `Account` object with the default fields.
     *
     * @param string|Account $accessToken [Required] Access token of the Telegraph account.
     * @param string|null $shortName [Optional, 1-32 characters] New account name.
     * @param string|null $authorName [Optional, 0-128 characters] New default author name used when creating new articles.
     * @param string|null $authorUrl [Optional, 0-512 characters] Can be any link, not necessarily to a Telegram profile or channel.
     * @return Account
     */
    public function editAccountInfo(
        string|Account $accessToken,
        ?string $shortName = null,
        ?string $authorName = null,
        ?string $authorUrl = null,
    ): Account {
        $response = $this->api(__FUNCTION__, [
            'access_token' => is_string($accessToken) ? $accessToken : $accessToken->getAccessToken(),
            'short_name' => $shortName,
            'author_name' => $authorName,
            'author_url' => $authorUrl,
        ]);

        return new Account($response, client: $this);
    }

    /**
     * Get information about a Telegraph account.
     *
     * Available fields: `short_name`, `author_name`, `author_url`, `auth_url`, `page_count`.
     *
     * @param string|Account $accessToken [Required] Access token of the Telegraph account.
     * @param string[] $fields [Required] List of account fields to return.
     * @return Account
     */
    public function getAccountInfo(
        string|Account $accessToken,
        array $fields = ['short_name', 'author_name', 'author_url', 'auth_url', 'page_count'],
    ): Account {
        $response = $this->api(__FUNCTION__, [
            'access_token' => is_string($accessToken) ? $accessToken : $accessToken->getAccessToken(),
            'fields' => $fields,
        ]);

        return new Account($response, client: $this);
    }

    /**
     * Revoke `access_token` and generate a new one.
     *
     * For example, if the user would like to reset all connected sessions,
     * or you have reasons to believe the token was compromised.
     *
     * On success, returns an `Account` object with new `access_token` and `auth_url` fields.
     *
     * @param string|Account $accessToken [Required] Access token of the Telegraph account.
     * @return Account
     */
    public function revokeAccessToken(string|Account $accessToken): Account
    {
        $response = $this->api(__FUNCTION__, [
            'access_token' => is_string($accessToken) ? $accessToken : $accessToken->getAccessToken(),
        ]);

        return new Account($response, client: $this);
    }

    /**
     * Create a new Telegraph page.
     *
     * On success, returns a `Page` object.
     *
     * @param string|Account $accessToken [Required] Access token of the Telegraph account.
     * @param string $title [Required, 1-256 characters] Page title.
     * @param string|NodeElement[] $content [Required, up to 64 KB] Content of the page.
     * @param string|null $authorName [Optional, 0-128 characters] Author name, displayed below the article's title.
     * @param string|null $authorUrl [Optional, 0-512 characters] Can be any link, not necessarily to a Telegram profile or channel.
     * @param bool $returnContent [Optional] If `true`, a content field will be returned in the `Page` object.
     * @return Page
     */
    public function createPage(
        string|Account $accessToken,
        string $title,
        string|array $content,
        ?string $authorName = null,
        ?string $authorUrl = null,
        bool $returnContent = false
    ): Page {
        $response = $this->api(__FUNCTION__, [
            'access_token' => is_string($accessToken) ? $accessToken : $accessToken->getAccessToken(),
            'title' => $title,
            'author_name' => $authorName ?? $accessToken instanceof Account ? $accessToken->getAuthorName() : null,
            'author_url' => $authorUrl ?? $accessToken instanceof Account ? $accessToken->getAuthorUrl() : null,
            'return_content' => $returnContent,
            'content' => is_array($content)
                ? json_encode($content)
                : json_encode([new NodeElement('p', $content)]),
        ]);

        return new Page($response);
    }

    /**
     * Edit an existing Telegraph page.
     *
     * On success, returns a `Page` object.
     *
     * @param string|Account $accessToken [Required] Access token of the Telegraph account.
     * @param string|Page $path [Required] Path to the Telegraph page (e.g. `http://telegra.ph/Title-12-31` or `Title-12-31`).
     * @param string $title [Required, 1-256 characters] Page title.
     * @param string|NodeElement[] $content [Required, up to 64 KB] Content of the page.
     * @param string|null $authorName [Optional, 0-128 characters] Author name, displayed below the article's title.
     * @param string|null $authorUrl [Optional, 0-512 characters] Can be any link, not necessarily to a Telegram profile or channel.
     * @param bool $returnContent [Optional] If `true`, a content field will be returned in the `Page` object.
     * @return Page
     */
    public function editPage(
        string|Account $accessToken,
        string|Page $path,
        string $title,
        string|array $content,
        ?string $authorName = null,
        ?string $authorUrl = null,
        bool $returnContent = false
    ): Page {
        $response = $this->api(__FUNCTION__ . '/' . $this->processPath($path), [
            'access_token' => is_string($accessToken) ? $accessToken : $accessToken->getAccessToken(),
            'title' => $title,
            'content' => json_encode($content),
            'author_name' => $authorName ?? $accessToken instanceof Account ? $accessToken->getAuthorName() : null,
            'author_url' => $authorUrl ?? $accessToken instanceof Account ? $accessToken->getAuthorUrl() : null,
            'return_content' => $returnContent,
        ]);

        return new Page($response);
    }

    /**
     * Get a Telegraph page.
     *
     * Returns a `Page` object on success.
     *
     * @param string|Page $path [Required] Path to the Telegraph page (e.g. `http://telegra.ph/Title-12-31` or `Title-12-31`).
     * @param bool $returnContent [Optional] If `true`, content field will be returned in `Page` object.
     * @return Page
     */
    public function getPage(string|Page $path, bool $returnContent = false): Page
    {
        $response = $this->api(__FUNCTION__ . '/' . $this->processPath($path), [
            'return_content' => $returnContent,
        ]);

        return new Page($response);
    }

    /**
     * Get a list of pages belonging to a Telegraph account.
     *
     * Returns a `PageList` object, sorted by most recently created pages first.
     *
     * @param string|Account $accessToken [Required] Access token of the Telegraph account.
     * @param int $offset [Optional] Sequential number of the first page to be returned.
     * @param int $limit [Optional, 0-200] Limits the number of pages to be retrieved.
     * @return PageList
     */
    public function getPageList(string|Account $accessToken, int $offset = 0, int $limit = 50): PageList
    {
        $response = $this->api(__FUNCTION__, [
            'access_token' => is_string($accessToken) ? $accessToken : $accessToken->getAccessToken(),
            'offset' => $offset,
            'limit' => $limit,
        ]);

        return new PageList($response);
    }

    /**
     * Get a list of pages belonging to a Telegraph account by pagination.
     *
     * @param string|Account $accessToken
     * @param int $offset
     * @param int $limit
     * @param Closure $callback
     * @return void
     */
    public function getPageListWithPagination(
        string|Account $accessToken,
        int $offset = 0,
        int $limit = 50,
        Closure $callback = new Closure,
    ): void {
        $token = is_string($accessToken) ? $accessToken : $accessToken->getAccessToken();

        $pages = $this->getPageList($token, $offset, $limit);

        $totalCount = $pages->getTotalCount();

        while ($totalCount > $offset) {
            while ($page = $pages->next()) {
                call_user_func($callback, $page);
            }

            $offset += $limit;

            $pages = $this->getPageList($token, $offset, $limit);
        }
    }

    /**
     * Get the number of views for a Telegraph article.
     *
     * Returns the total number of page views.
     *
     * @param string|Page $path [Required] Path to the Telegraph page (e.g. `http://telegra.ph/Title-12-31` or `Title-12-31`).
     * @param int|null $year [Required if month is passed, 2000-2100] If passed, the number of page views for the requested year will be returned.
     * @param int|null $month [Required if day is passed, 1-12] If passed, the number of page views for the requested month will be returned.
     * @param int|null $day [Required if hour is passed, 1-31] If passed, the number of page views for the requested day will be returned.
     * @param int|null $hour [Optional, 0-24] If passed, the number of page views for the requested hour will be returned.
     * @return int
     */
    public function getViews(
        string|Page $path,
        int $year = null,
        int $month = null,
        int $day = null,
        int $hour = null
    ): int {
        $response = $this->api(__FUNCTION__, array_filter([
            'path' => $this->processPath($path),
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
        ]));

        return $response['views'];
    }

    /**
     * @param string|Page $path
     * @return string
     */
    protected function processPath(string|Page $path): string
    {
        if ($path instanceof Page) {
            return $path->getPath();
        }

        if (str_starts_with($path, 'http')) {
            $pathArray = explode('/', $path);
            return end($pathArray);
        }

        return $path;
    }
}
