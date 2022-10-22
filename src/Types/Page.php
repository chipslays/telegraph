<?php

namespace Telegraph\Types;

class Page
{
    /**
     * Path to the page.
     *
     * @var string
     */
    protected string $path;

    /**
     * URL of the page.
     *
     * @var string
     */
    protected string $url;

    /**
     * Title of the page.
     *
     * @var string
     */
    protected string $title;

    /**
     * Description of the page.
     *
     * @var string|null
     */
    protected ?string $description;

    /**
     * Name of the author, displayed below the title.
     *
     * @var string|null
     */
    protected ?string $authorName;

    /**
     * Profile link, opened when users click on the author's name below the title.
     *
     * Can be any link, not necessarily to a Telegram profile or channel.
     *
     * @var string|null
     */
    protected ?string $authorUrl;

    /**
     * Image URL of the page.
     *
     * @var string|null
     */
    protected ?string $imageUrl;

    /**
     * Content of the page.
     *
     * @var array|null
     */
    protected ?array $content;

    /**
     * Number of page views for the page.
     *
     * @var integer
     */
    protected int $views;

    /**
     * Only returned if `access_token` passed.
     *
     * `True`, if the target Telegraph account can edit the page.
     *
     * @var boolean|null
     */
    protected ?bool $canEdit;

    /**
     * Constructor.
     *
     * @param array $page
     */
    public function __construct(array $page)
    {
        $this->path = $page['path'];
        $this->url = $page['url'];
        $this->title = $page['title'];
        $this->description = mb_strlen(trim($page['description'])) > 0 ? $page['description'] : null;
        $this->authorName = $page['author_name'] ?? null;
        $this->authorUrl = $page['author_url'] ?? null;
        $this->imageUrl = $page['image_url'] ?? null;
        $this->content = $page['content'] ?? null;
        $this->views = $page['views'];
        $this->canEdit = $page['can_edit'] ?? null;
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
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    /**
     * @return string|null
     */
    public function getAuthUrl(): ?string
    {
        return $this->authorUrl;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

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
     * @return bool|null
     */
    public function canEdit(): ?bool
    {
        return $this->canEdit;
    }

    /**
     * Get text from page.
     *
     * @param array|null $content
     * @return string
     */
    public function getText(?array $content = null): string
    {
        $text = '';

        foreach ($content ?? $this->content as $element) {
            if (is_string($element)) {
                $text .= $element . "\n";
                continue;
            }

            if (!isset($element['tag'])) continue;

            if ($element['tag'] == 'br' || $element['tag'] == 'hr') {
                $text .= "\n";
                continue;
            }

            if ($element['tag'] == 'img') {
                if (str_starts_with($element['attrs']['src'], '/')) {
                    $element['attrs']['src'] = 'https://telegra.ph' . $element['attrs']['src'];
                }
                $text .= "[IMAGE] -> " . $element['attrs']['src'] . "\n";
                continue;
            }

            if ($element['tag'] == 'iframe') {
                $text .= "\n[IFRAME] -> https://telegra.ph" . $element['attrs']['src'] . "\n";
                continue;
            }

            if ($element['tag'] == 'figcaption' && isset($element['children']) && is_string($element['children'][0]) && mb_strlen(trim($element['children'][0])) > 0) {
                $text .= "[CAPTION] -> ";
            }

            if (!isset($element['children'])) continue;

            foreach ($element['children'] as $child) {
                if (is_array($child)) {
                    $text .= $this->getText([$child]) . "\n";
                    continue;
                }

                $text .= $child . "\n";
            }
        }

        $text = trim(preg_replace("/(\r?\n){2,}/", "\n\n", $text));

        return $text;
    }
}
