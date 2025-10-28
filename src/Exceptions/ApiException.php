<?php

declare(strict_types=1);

namespace Telegraph\Exceptions;

/**
 * Telegraph API Exception
 *
 * Thrown when Telegraph API returns an error response.
 * Contains error message from API (e.g., "SHORT_NAME_REQUIRED",
 * "PAGE_NOT_FOUND", "ACCESS_TOKEN_INVALID").
 *
 * Common API errors:
 * - SHORT_NAME_REQUIRED: Account short name is missing or too short
 * - ACCESS_TOKEN_INVALID: Invalid or expired access token
 * - PAGE_NOT_FOUND: Requested page doesn't exist
 * - PAGE_ACCESS_DENIED: No permission to edit page
 * - CONTENT_TOO_BIG: Page content exceeds 64KB limit
 * - TITLE_REQUIRED: Page title is missing
 *
 * @see https://telegra.ph/api API error codes
 *
 * @example Handling specific API errors
 * try {
 *     $page = $telegraph->page('Some-Page')->edit('Title', 'Content');
 * } catch (ApiException $e) {
 *     $message = $e->getMessage();
 *
 *     if (str_contains($message, 'PAGE_NOT_FOUND')) {
 *         echo "Page doesn't exist";
 *     } elseif (str_contains($message, 'ACCESS_TOKEN_INVALID')) {
 *         echo "Invalid token - please login again";
 *     } elseif (str_contains($message, 'PAGE_ACCESS_DENIED')) {
 *         echo "You don't have permission to edit this page";
 *     } else {
 *         echo "API error: " . $message;
 *     }
 * }
 *
 * @example Network error handling
 * try {
 *     $account = $telegraph->createAccount('MyBlog');
 * } catch (ApiException $e) {
 *     // This catches both API errors and network failures
 *     error_log("Telegraph API failed: " . $e->getMessage());
 *     // Implement retry logic or fallback
 * }
 */
class ApiException extends TelegraphException
{
}
