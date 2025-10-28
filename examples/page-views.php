<?php

use Telegraph\TelegraphClient;

require __DIR__ . '/../vendor/autoload.php';

$telegraph = new TelegraphClient();

$page = $telegraph->page('Sample-Page-12-15');
$views = $page->views();

echo $views->views; // 4265
