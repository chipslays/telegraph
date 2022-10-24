<?php

use Telegraph\Client;
use Telegraph\Element;

require __DIR__ . '/../vendor/autoload.php';

$client = new Client;

$account = $client->createAccount('chipslays', 'chipslays', 'https://github.com/chipslays');

$page = $account->createPage('Hello World', [
    Element::bigHeading([
        Element::link('More Examples Here', 'https://github.com/chipslays/telegraph/tree/v3.x/examples'),
    ]),
]);

echo $page->getUrl(); // https://telegra.ph/Hello-World-10-24-49

