<?php

use Telegraph\Telegraph;

require __DIR__ . '/../vendor/autoload.php';

$token = '13a7d37c495d339a2002454f7bfd2d32eb205d5f29c3b7dc3bf5d8ba15d0';

/**
 * Variant 1: Pass token in Telegraph constructor
 * (This is the recommended approach)
 */
$telegraph = new Telegraph($token);
$account = $telegraph->account();
$page = $account->createPage(
    title: 'Hello World',
    content: 'This is a Hello World example.'
);

echo $page->url();

/**
 * Variant 2: Create new Telegraph instance when needed
 * (For working with multiple accounts)
 */
$telegraph1 = new Telegraph($token);
$page = $telegraph1->account()->createPage(
    title: 'Hello World',
    content: 'This is a Hello World example.'
);

/**
 * Variant 3: Switch between accounts dynamically
 */
class AccountManager
{
    private array $clients = [];

    public function addAccount(string $name, string $token): void
    {
        $this->clients[$name] = new Telegraph($token);
    }

    public function getAccount(string $name)
    {
        return $this->clients[$name]->account();
    }
}

$manager = new AccountManager();
$manager->addAccount('account1', $token);
$manager->addAccount('account2', 'another_token');

$page = $manager->getAccount('account1')->createPage(
    title: 'Hello World',
    content: 'Example'
);
