<?php

use Telegraph\Client;
use Telegraph\Element;
use Telegraph\Types\NodeElement;

require __DIR__ . '/../vendor/autoload.php';

$imageUrl = 'https://avatars.githubusercontent.com/u/19103498?v=4';

$client = new Client;

$account = $client->createAccount('chipslays', 'chipslays', 'https://github.com/chipslays');

$page = $account->createPage('Article With Images', [
    // can pass image by url or local (without File class!)
    Element::image($imageUrl), // without caption
    Element::image($imageUrl, 'Image With Caption'),

    // or upload image by manual
    new NodeElement('img', attrs: ['src' => $imageUrl]),
]);

echo $page->getUrl(); // https://telegra.ph/Hello-World-10-24-49

