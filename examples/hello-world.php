<?php

use Telegraph\Client;

require __DIR__ . '/../vendor/autoload.php';

$client = new Client;

$account = $client->createAccount('johndoe', 'John Doe', 'https://example.com');

$page = $account->createPage('Hello World', 'This is a Hello World example.');

echo $page->getUrl(); // https://telegra.ph/Hello-World-10-21-12

