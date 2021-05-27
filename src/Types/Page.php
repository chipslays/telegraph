<?php

namespace Chipslays\Telegraph\Types;

use Chipslays\Collection\Collection;

/**
 * Class Page
 *
 * This object represents a page on Telegraph.
 */
class Page extends AbstractType
{
    /**
     * Path to the page.
     *
     * @var string
     */
    private $path;

    /**
     * URL of the page.
     *
     * @var string
     */
    private $url;

    /**
     * Title of the page.
     *
     * @var string
     */
    private $title;

    /**
     * Description of the page.
     *
     * @var string
     */
    private $description;

    /**
     * Name of the author, displayed below the title.
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
     * Image URL of the page.
     *
     * @var string
     */
    private $imageUrl;

    /**
     * Content of the page.
     *
     * @var string|NodeElement[]
     */
    private $content;

    /**
     * Number of page views for the page.
     *
     * @var int
     */
    private $views;

    /**
     * Only returned if access_token passed.
     * True, if the target Telegraph account can edit the page.
     *
     * @var bool
     */
    private $canEdit;

    public function __construct(Collection $data)
    {
        $this->data = $data;
        $this->path = $data->get('path');
        $this->url = $data->get('url');
        $this->title = $data->get('title', '');
        $this->description = $data->get('description', '');
        $this->authorName = $data->get('author_name', '');
        $this->authorUrl = $data->get('author_url', '');
        $this->imageUrl = $data->get('image_url', '');
        $this->content = $data->get('content', null);
        $this->views = $data->get('views', 0);
        $this->canEdit = $data->get('can_edit', false);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
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
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return string|NodeElement[]
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @return bool
     */
    public function isCanEdit(): bool
    {
        return $this->canEdit;
    }
}
