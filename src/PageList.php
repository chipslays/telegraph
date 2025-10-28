<?php

declare(strict_types=1);

namespace Telegraph;

use Telegraph\DTO\PageData;

/**
 * Telegraph Page List
 *
 * Represents a collection of Telegraph pages belonging to an account.
 * Implements Iterator for foreach support and Countable for count() function.
 * Pages are sorted by creation date (most recent first).
 *
 * @implements \Iterator<int, Page>
 * @implements \Countable
 * @see https://telegra.ph/api
 */
class PageList implements \Iterator, \Countable
{
    /** @var Page[] Array of Page instances */
    private array $pages = [];

    /** @var int Current iterator position */
    private int $position = 0;

    /**
     * Create PageList instance
     *
     * Constructs a list of pages from API response data.
     * Each page in the response is converted to a Page instance.
     *
     * @param TelegraphClient $client Telegraph client for API requests
     * @param array $data API response containing pages array
     * @param string|null $accessToken Access token for page operations
     */
    public function __construct(
        private readonly TelegraphClient $client,
        array $data,
        private readonly ?string $accessToken = null,
    ) {
        $this->pages = array_map(
            fn($page) => new Page(
                client: $this->client,
                data: PageData::fromArray($page),
                accessToken: $this->accessToken,
            ),
            $data['pages'] ?? []
        );
    }

    /**
     * Get total number of pages in the list
     *
     * Alias for count() method. Returns the number of pages
     * returned by the API request (respects limit parameter).
     *
     * @return int Number of pages in this list
     *
     * @example
     * $pages = $account->pages(limit: 10);
     * echo "Retrieved: " . $pages->total();
     */
    public function total(): int
    {
        return count($this->pages);
    }

    /**
     * Get current page in iteration
     *
     * Returns the Page instance at current iterator position.
     * Part of Iterator interface implementation.
     *
     * @return Page Current page instance
     */
    public function current(): Page
    {
        return $this->pages[$this->position];
    }

    /**
     * Get current iterator key
     *
     * Returns the current position/index in the iteration.
     * Part of Iterator interface implementation.
     *
     * @return int Current position (0-based index)
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * Move iterator to next position
     *
     * Advances the internal iterator position by one.
     * Part of Iterator interface implementation.
     *
     * @return void
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * Reset iterator to first position
     *
     * Resets the internal iterator position to the beginning.
     * Called automatically at the start of foreach loops.
     * Part of Iterator interface implementation.
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Check if current position is valid
     *
     * Returns true if there is a page at the current position.
     * Used by foreach to determine when to stop iteration.
     * Part of Iterator interface implementation.
     *
     * @return bool True if current position has a page, false otherwise
     */
    public function valid(): bool
    {
        return isset($this->pages[$this->position]);
    }

    /**
     * Count pages in the list
     *
     * Returns the number of pages in this list.
     * Enables using count() function on PageList instances.
     * Part of Countable interface implementation.
     *
     * @return int Number of pages
     *
     * @example
     * $pages = $account->pages();
     * echo count($pages); // Works thanks to Countable
     */
    public function count(): int
    {
        return count($this->pages);
    }

    /**
     * Convert page list to array
     *
     * Returns all Page instances as a standard PHP array.
     * Useful when you need to manipulate the collection using
     * array functions or pass it to other code.
     *
     * @return Page[] Array of Page instances
     *
     * @example
     * $pages = $account->pages(limit: 5);
     * $array = $pages->toArray();
     *
     * // Use array functions
     * $titles = array_map(fn($p) => $p->title(), $array);
     *
     * // Filter pages
     * $filtered = array_filter($array, fn($p) => $p->viewsCount() > 100);
     */
    public function toArray(): array
    {
        return $this->pages;
    }
}
