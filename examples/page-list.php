<?php

use Telegraph\Client;

require __DIR__ . '/../vendor/autoload.php';

$client = new Client;

$token = '0baab53f5dc2ac3a3ec96253a634224eb63e908dd2e00aa082a245e3fcb9';
$offset = 0;
$limit = 50;

$pages = $client->getPageList($token, $offset, $limit);

while ($page = $pages->next()) {
    echo $page->getTitle() . ', views: ' . $page->getViews() . PHP_EOL;
}