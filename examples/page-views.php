<?php

use Telegraph\Telegraph;

require __DIR__ . '/../vendor/autoload.php';

$telegraph = new Telegraph();

$page = $telegraph->page('Sample-Page-12-15');
$views = $page->views();

echo $views->views; // 4265
