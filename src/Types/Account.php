<?php

namespace Telegraph\Types;

use CurlHandle;
use Telegraph\Client;

/**
 * Class represents a Telegraph account.
 */
class Account
{
    protected $default = [
        'short_name' => '',
        'author_name' => '',
        'author_url' => '',
        'access_token' => '0baab53f5dc2ac3a3ec96253a634224eb63e908dd2e00aa082a245e3fcb9', // public token, it's ok!
        'auth_url' => '',
    ];

    /**
     * Account name, helps users with several accounts remember which they are currently using.
     *
     * Displayed to the user above the "Edit/Publish" button on Telegra.ph, other users don't see this name.
     *
     * @var string
     */
    protected string $shortName;

    /**
     * Default author name used when creating new articles.
     *
     * @var string
     */
    protected string $authorName;

    /**
     * Profile link, opened when users click on the author's name below the title.
     * Can be any link, not necessarily to a Telegram profile or channel.
     *
     * @var string
     */
    protected string $authorUrl;

    /**
     * Access token of the Telegraph account.
     *
     * Only returned by the `createAccount` and `revokeAccessToken` method.
     *
     * @var string
     */
    protected string $accessToken;

    /**
     * URL to authorize a browser on telegra.ph and connect it to a Telegraph account.
     *
     * This URL is valid for only one use and for 5 minutes only.
     *
     * @var string
     */
    protected string $authUrl;

    /**
     * Number of pages belonging to the Telegraph account.
     *
     * @var integer|null
     */
    protected ?int $pageCount;

    /**
     * Constructor.
     *
     * @param array|string|null|null $account
     * @param CurlHandle|null $httpClient
     */
    public function __construct(
        array|string|null $account = null,
        protected ?Client $client = null
    ) {
        $this->setupAccount($account);
    }

    /**
     * @param array|string|null $account
     * @return void
     */
    protected function setupAccount(array|string|null $account): void
    {
        if (!$account) {
            $account = $this->default;
        }

        if (is_string($account)) {
            $account = [
                'short_name' => '',
                'author_name' => '',
                'author_url' => '',
                'access_token' => $account,
                'auth_url' => '',
            ];
        }

        $this->shortName = $account['short_name'];
        $this->authorName = $account['author_name'];
        $this->authorUrl = $account['author_url'];
        $this->accessToken = $account['access_token'];
        $this->authUrl = $account['auth_url'];
    }

    /**
     * @return string
     */
    public function getShortName(): string
    {
        return $this->shortName;
    }

    /**
     * @return string
     */
    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    /**
     * @return string
     */
    public function getAuthorUrl(): string
    {
        return $this->authorUrl;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getAuthUrl(): string
    {
        return $this->authUrl;
    }

    /**
     * @return int|null
     */
    public function getPageCount(): ?int
    {
        return $this->pageCount;
    }

    /**
     * Get information about a Telegraph account.
     *
     * Available fields: `short_name`, `author_name`, `author_url`, `auth_url`, `page_count`.
     *
     * @param array $fields [Required] List of account fields to return.
     * @return Account
     */
    public function getAccountInfo(
        array $fields = ['short_name', 'author_name', 'author_url', 'auth_url', 'page_count'],
    ): Account {
        return $this->client->getAccountInfo($this, $fields);
    }

    /**
     * Update information about a Telegraph account.
     *
     * Pass only the parameters that you want to edit.
     *
     * On success, returns an `Account` object with the default fields.
     *
     * @param string|null $shortName [Optional, 1-32 characters] New account name.
     * @param string|null $authorName [Optional, 0-128 characters] New default author name used when creating new articles.
     * @param string|null $authorUrl [Optional, 0-512 characters] Can be any link, not necessarily to a Telegram profile or channel.
     * @return Account
     */
    public function editAccountInfo(
        ?string $shortName = null,
        ?string $authorName = null,
        ?string $authorUrl = null,
    ): Account {
        return $this->client->editAccountInfo($this, $shortName, $authorName, $authorUrl);
    }

    /**
     * Revoke `access_token` and generate a new one.
     *
     * For example, if the user would like to reset all connected sessions,
     * or you have reasons to believe the token was compromised.
     *
     * On success, returns an `Account` object with new `access_token` and `auth_url` fields.
     *
     * @return Account
     */
    public function revokeAccessToken(): Account
    {
        return $this->client->revokeAccessToken($this);
    }

    /**
     * Create a new Telegraph page.
     *
     * On success, returns a `Page` object.
     *
     * @param string $title [Required, 1-256 characters] Page title.
     * @param string|array $content [Required, up to 64 KB] Content of the page.
     * @param string|null $authorName [Optional, 0-128 characters] Author name, displayed below the article's title.
     * @param string|null $authorUrl [Optional, 0-512 characters] Can be any link, not necessarily to a Telegram profile or channel.
     * @param boolean $returnContent [Optional] If `true`, a content field will be returned in the `Page` object.
     * @return Page
     */
    public function createPage(
        string $title,
        string|array $content,
        ?string $authorName = null,
        ?string $authorUrl = null,
        bool $returnContent = false
    ): Page {
        return $this->client->createPage($this, $title, $content, $authorName, $authorUrl, $returnContent);
    }

    /**
     * Edit an existing Telegraph page.
     *
     * On success, returns a `Page` object.
     *
     * @param string|Page $path [Required] Path to the Telegraph page (e.g. `http://telegra.ph/Title-12-31` or `Title-12-31`).
     * @param string $title [Required, 1-256 characters] Page title.
     * @param string|array $content [Required, up to 64 KB] Content of the page.
     * @param string|null $authorName [Optional, 0-128 characters] Author name, displayed below the article's title.
     * @param string|null $authorUrl [Optional, 0-512 characters] Can be any link, not necessarily to a Telegram profile or channel.
     * @param boolean $returnContent [Optional] If `true`, a content field will be returned in the `Page` object.
     * @return Page
     */
    public function editPage(
        string|Page $path,
        string $title,
        string|array $content,
        ?string $authorName = null,
        ?string $authorUrl = null,
        bool $returnContent = false
    ): Page {
        return $this->client->editPage($this, $path, $title, $content, $authorName, $authorUrl, $returnContent);
    }

    /**
     * Get a list of pages belonging to a Telegraph account.
     *
     * Returns a `PageList` object, sorted by most recently created pages first.
     *
     * @param integer $offset [Optional] Sequential number of the first page to be returned.
     * @param integer $limit [Optional, 0-200] Limits the number of pages to be retrieved.
     * @return PageList
     */
    public function getPageList(int $offset = 0, int $limit = 50): PageList
    {
        return $this->client->getPageList($offset, $limit);
    }
}
