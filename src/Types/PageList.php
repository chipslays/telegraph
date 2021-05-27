<?php

namespace Chipslays\Telegraph\Types;

use Chipslays\Collection\Collection;

/**
 * Class PageList
 *
 * This object represents a list of Telegraph articles belonging to an account.
 * Most recently created articles first.
 */
class PageList
{
    /**
     * Total number of pages belonging to the target Telegraph account.
     *
     * @var int
     */
    private $totalCount;

    /**
     * Requested pages of the target Telegraph account.
     *
     * @var Page[]
     */
    private $pages = [];

    public function __construct(Collection $data)
    {
        $this->data = $data;
        $this->totalCount = $data->get('total_count', 0);

        foreach ($data->get('pages', []) as $page) {
            $this->pages[] = new Page(collection($page));
        }
    }

    /**
     * Get total number of pages belonging to the target Telegraph account.
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * Get requested pages of the target Telegraph account.
     *
     * @return Page[]
     */
    public function getPages()
    {
        return $this->pages;
    }

    public function next()
    {
        return array_shift($this->pages);
    }
}
