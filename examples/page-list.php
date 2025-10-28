<?php

use Telegraph\TelegraphClient;

require __DIR__ . '/../vendor/autoload.php';

$token = '13a7d37c495d339a2002454f7bfd2d32eb205d5f29c3b7dc3bf5d8ba15d0';

$telegraph = new TelegraphClient($token);
$account = $telegraph->account();

$offset = 0;
$limit = 50;

$pages = $account->pages(offset: $offset, limit: $limit);

foreach ($pages as $page) {
    echo $page->title() . ', views: ' . $page->viewsCount() . PHP_EOL;
}
