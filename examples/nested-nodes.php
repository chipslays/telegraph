<?php

use Telegraph\TelegraphClient;

require __DIR__ . '/../vendor/autoload.php';

$telegraph = new TelegraphClient();

$account = $telegraph->createAccount(
    shortName: 'chipslays',
    authorName: 'chipslays',
    authorUrl: 'https://github.com/chipslays'
);

$page = $account->createPage(
    title: 'Hello World',
    content: $telegraph->content()
        ->h3(function($b) {
            $b->link('More Examples Here', 'https://github.com/chipslays/telegraph/tree/v5.x/examples');
        })
        ->build()
);

echo $page->url(); // https://telegra.ph/Hello-World-10-28-77
