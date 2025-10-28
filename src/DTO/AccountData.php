<?php

declare(strict_types=1);

namespace Telegraph\DTO;

/**
 * Telegraph Account Data Transfer Object
 *
 * Immutable data object representing Telegraph account information
 * returned by API methods (createAccount, getAccountInfo, editAccountInfo, revokeAccessToken).
 *
 * All properties are readonly and set via constructor.
 * Use fromArray() factory method to create from API response.
 *
 * @see https://telegra.ph/api Account object documentation
 */
readonly class AccountData
{
    /**
     * Create AccountData instance
     *
     * @param string $shortName Account name (1-32 chars), displayed in editor to user
     * @param string|null $authorName Default author name for new articles (0-128 chars)
     * @param string|null $authorUrl Default author profile link (0-512 chars)
     * @param string|null $accessToken Access token for API authentication (only in createAccount/revokeAccessToken)
     * @param string|null $authUrl URL to authorize browser on telegra.ph (only in createAccount/revokeAccessToken, valid 5 min)
     * @param int|null $pageCount Total number of pages belonging to account (only when requested in getAccountInfo)
     */
    public function __construct(
        public string $shortName,
        public ?string $authorName = null,
        public ?string $authorUrl = null,
        public ?string $accessToken = null,
        public ?string $authUrl = null,
        public ?int $pageCount = null,
    ) {}

    /**
     * Create AccountData from API response array
     *
     * Factory method that constructs AccountData from Telegraph API response.
     * Handles optional fields gracefully with null coalescing.
     *
     * @param array $data API response array with snake_case keys
     * @return self New AccountData instance
     *
     * @example
     * $response = ['short_name' => 'MyBlog', 'author_name' => 'John Doe'];
     * $accountData = AccountData::fromArray($response);
     */
    public static function fromArray(array $data): self
    {
        return new self(
            shortName: $data['short_name'],
            authorName: $data['author_name'] ?? null,
            authorUrl: $data['author_url'] ?? null,
            accessToken: $data['access_token'] ?? null,
            authUrl: $data['auth_url'] ?? null,
            pageCount: $data['page_count'] ?? null,
        );
    }
}
