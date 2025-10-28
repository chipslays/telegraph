<?php

use Telegraph\TelegraphClient;

require __DIR__ . '/../vendor/autoload.php';

$telegraph = new TelegraphClient();

$account = $telegraph->createAccount(
    shortName: 'johndoe',
    authorName: 'John Doe',
    authorUrl: 'https://example.com'
);

$page = $account->createPage(
    title: 'Hello World',
    content: 'This is a Hello World example.'
);

echo $page->url(); // https://telegra.ph/Hello-World-10-28-76
