<?php

use Chipslays\Telegraph\Client;

require __DIR__ . '/../vendor/autoload.php';

$client = new Client;

echo $client->getViews('Sample-Page-12-15'); // 1816