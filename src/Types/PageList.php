<?php

namespace Telegraph\Types;

/**
 * Class represents a list of Telegraph articles belonging to an account.
 *
 * Most recently created articles first.
 */
class PageList
{
    /**
     * Total number of pages belonging to the target Telegraph account.
     *
     * @var int
     */
    protected int $totalCount;

    /**
     * Requested pages of the target Telegraph account.
     *
     * @var Page[]
     */
    private array $pages = [];

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        foreach ($data['pages'] as $page) {
            $this->pages[] = new Page($page);
        }

        $this->totalCount = $data['total_count'];
    }

    /**
     * Get total number of pages belonging to the target Telegraph account.
     *
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @return Page[]
     */
    public function getPages(): array
    {
        return $this->pages;
    }

    /**
     * Shift element from array of pages.
     *
     * Useful for use in loops like `while`.
     *
     * @return Page|null
     */
    public function next(): ?Page
    {
        return array_shift($this->pages);
    }
}
