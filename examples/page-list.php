<?php

use Chipslays\Telegraph\Client;

require __DIR__ . '/../vendor/autoload.php';

$client = new Client;

$token = 'b968da509bb76866c35425099bc0989a5ec3b32997d55286c657e6994bbb';
$offset = 0;
$limit = 5;

$pages = $client->getPageList($token, $offset, $limit);

while ($page = $pages->next()) {
    echo $page->getTitle() . ' - ' . $page->getViews() . PHP_EOL;
}