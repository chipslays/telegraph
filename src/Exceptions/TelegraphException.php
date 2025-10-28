<?php

declare(strict_types=1);

namespace Telegraph\Exceptions;

/**
 * Base Telegraph Exception
 *
 * Base exception class for all Telegraph library exceptions.
 * Extends standard PHP Exception.
 *
 * Catch this exception type to handle all Telegraph-related errors:
 * - API communication errors (ApiException)
 * - Validation errors
 * - Configuration errors
 *
 * @example Basic error handling
 * try {
 *     $account = $telegraph->createAccount('MyBlog');
 * } catch (TelegraphException $e) {
 *     echo "Telegraph error: " . $e->getMessage();
 * }
 *
 * @example Catching specific errors
 * try {
 *     $page = $account->createPage('Title', 'Content');
 * } catch (ApiException $e) {
 *     echo "API error: " . $e->getMessage();
 * } catch (TelegraphException $e) {
 *     echo "Other Telegraph error: " . $e->getMessage();
 * }
 */
class TelegraphException extends \Exception
{
}
