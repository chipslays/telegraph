<?php

namespace Chipslays\Telegraph\Types;

use Chipslays\Collection\Collection;

/**
 * Class Account
 *
 * This object represents a Telegraph account.
 */
class Account extends AbstractType
{
    /**
     * Account name, helps users with several accounts remember which they are currently using.
     * Displayed to the user above the "Edit/Publish" button on Telegra.ph, other users don't see this name.
     *
     * @var string
     */
    private $shortName;

    /**
     * Default author name used when creating new articles.
     *
     * @var string
     */
    private $authorName;

    /**
     * Profile link, opened when users click on the author's name below the title.
     * Can be any link, not necessarily to a Telegram profile or channel.
     *
     * @var string
     */
    private $authorUrl;

    /**
     * Only returned by the createAccount and revokeAccessToken method. Access token of the Telegraph account.
     *
     * @var string
     */
    private $accessToken;

    /**
     * URL to authorize a browser on telegra.ph and connect it to a Telegraph account.
     * This URL is valid for only one use and for 5 minutes only.
     *
     * @var string
     */
    private $authUrl;

    /**
     * Number of pages belonging to the Telegraph account.
     *
     * @var int
     */
    private $pageCount;

    public function __construct(Collection $data)
    {
        $this->data = $data;
        $this->shortName = $data->get('short_name', '');
        $this->authorName = $data->get('author_name', '');
        $this->authorUrl = $data->get('author_url', '');
        $this->authUrl = $data->get('auth_url', '');
        $this->accessToken = $data->get('access_token', '');
        $this->pageCount = $data->get('page_count', 0);
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
    public function getPageCount(): int
    {
        return $this->pageCount;
    }
}
