<?php

use Telegraph\Client;
use Telegraph\Types\Page;

require __DIR__ . '/../vendor/autoload.php';

$client = new Client;

$token = '0baab53f5dc2ac3a3ec96253a634224eb63e908dd2e00aa082a245e3fcb9';

// iteration of all pages until the last one
$client->getPageListWithPagination($token, offset: 0, limit: 50, function (Page $page) use ($client) {
    $details = $client->getPage($page, true); // get content of page

    $text = $details->getText(); // get text (its custom method)

    echo $text . PHP_EOL;
});
