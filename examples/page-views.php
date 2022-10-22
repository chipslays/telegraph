<?php

use Telegraph\Client;

require __DIR__ . '/../vendor/autoload.php';

$client = new Client();

echo $client->getViews('Sample-Page-12-15'); // 2939