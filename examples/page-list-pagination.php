<?php

use Telegraph\Client;
use Telegraph\Types\Page;

require __DIR__ . '/../vendor/autoload.php';

$client = new Client;

$token = '0baab53f5dc2ac3a3ec96253a634224eb63e908dd2e00aa082a245e3fcb9';

$client->getPageListWithPagination($token, 0, 50, function (Page $page) use ($client) {
    $details = $client->getPage($page, true);

    $text = $details->getText();

    echo $text;
});
