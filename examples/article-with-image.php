<?php

use Telegraph\Telegraph;

require __DIR__ . '/../vendor/autoload.php';

$imageUrl = 'https://avatars.githubusercontent.com/u/19103498?v=4';

$telegraph = new Telegraph();

$account = $telegraph->createAccount(
    shortName: 'chipslays',
    authorName: 'chipslays',
    authorUrl: 'https://github.com/chipslays'
);

$page = $account->createPage(
    title: 'Article With Images',
    content: $telegraph->content()
        // Simple image without caption
        ->img($imageUrl)

        // Image with caption using figure
        ->figure($imageUrl, 'Image With Caption')

        // Or use raw node array
        ->add(['tag' => 'img', 'attrs' => ['src' => $imageUrl]])

        ->build()
);

echo $page->url(); // https://telegra.ph/Article-With-Images-10-28
