<?php

use Telegraph\Client;
use Telegraph\Types\Account;

require __DIR__ . '/../vendor/autoload.php';

/**
 * 1 variant.
 *
 * Once pass token in Account.
 */
$client = new Client;
$account = new Account('0baab53f5dc2ac3a3ec96253a634224eb63e908dd2e00aa082a245e3fcb9', $client);
$page = $account->createPage('Hello World', 'This is a Hello World example.');

/**
 * 2 variant.
 *
 * Pass token in Client methods where it needed. (it more flexible)
 */

$token = '0baab53f5dc2ac3a3ec96253a634224eb63e908dd2e00aa082a245e3fcb9';

$client = new Client;

$page = $client->createPage($token, 'Hello World', 'This is a Hello World example.');

// Or pass Account instance as token.
$page = $client->createPage($account, 'Hello World', 'This is a Hello World example.');