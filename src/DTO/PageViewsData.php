<?php

declare(strict_types=1);

namespace Telegraph\DTO;

/**
 * Telegraph Page Views Data Transfer Object
 *
 * Immutable data object representing view statistics for a Telegraph page
 * returned by getViews API method.
 *
 * Contains view count for the requested time period (total, yearly,
 * monthly, daily, or hourly depending on parameters passed to getViews).
 *
 * @see https://telegra.ph/api PageViews object documentation
 */
readonly class PageViewsData
{
    /**
     * Create PageViewsData instance
     *
     * @param int $views Number of page views for the requested period
     */
    public function __construct(
        public int $views,
    ) {}

    /**
     * Create PageViewsData from API response array
     *
     * Factory method that constructs PageViewsData from Telegraph API response.
     *
     * @param array $data API response array containing 'views' key
     * @return self New PageViewsData instance
     *
     * @example
     * $response = ['views' => 12345];
     * $viewsData = PageViewsData::fromArray($response);
     * echo "Views: {$viewsData->views}";
     */
    public static function fromArray(array $data): self
    {
        return new self(
            views: $data['views'],
        );
    }
}
