<?php

use Telegraph\Telegraph;

require __DIR__ . '/../vendor/autoload.php';

$token = '13a7d37c495d339a2002454f7bfd2d32eb205d5f29c3b7dc3bf5d8ba15d0';

$telegraph = new Telegraph($token);
$account = $telegraph->account();

$offset = 0;
$limit = 1;

do {
    // Get pages batch
    $pages = $account->pages(offset: $offset, limit: $limit);
    $count = $pages->total();

    // Iterate through pages
    foreach ($pages as $page) {
        echo '[' . $page->viewsCount() . '] '
             . $page->title() . ' --> '
             . $page->url() . PHP_EOL;
    }

    $offset += $limit;

} while ($count === $limit);
