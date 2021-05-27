<?php

use Chipslays\Telegraph\Client;

require __DIR__ . '/../vendor/autoload.php';

$client = new Client;

/** create new account */
$account = $client->createAccount('chipslays', 'Alex', 'https://github.com/chipslays');
$token = $account->getAccessToken(); // store this token in safe place for reuse

/** create new page */
$page = $client->createPage($token, 'New page', 'Hello world!');

print_r($page->toArray());

// Array
// (
//     [path] => New-page-05-27
//     [url] => https://telegra.ph/New-page-05-27
//     [title] => New page
//     [description] =>
//     [views] => 0
//     [can_edit] => 1
// )
