# Telegraph API Client for PHP

Telegraph is an anonymous blogging platform, a free publishing tool created by Telegram.

## Installation

Install latest 3.x version via Composer.

```bash
composer require chipslays/telegraph
```

## Usage

### Easy Peasy Mode

```php
$client = new Telegraph\Client;

$account = $client->createAccount('johndoe', 'John Doe', 'https://example.com');

$page = $account->createPage('Hello World', 'This is a Hello World example.');

echo $page->getUrl(); // https://telegra.ph/Hello-World-10-21-12
```

### Hard Mode

```php
use Telegraph\Client;
use Telegraph\Element;
use Telegraph\File;
use Telegraph\Types\Account;
use Telegraph\Types\NodeElement;

$client = new Client;

$account = $client->createAccount('johndoe', 'John Doe', 'https://example.com');

$page = $account->createPage(
    'Rich Example',
    [
        Element::text("Line 1\nLine 2\nLine 3\n\n"),
        Element::line(),
        Element::bigHeading("H3 text"),
        Element::smallHeading("H4 text"),
        Element::line(),
        Element::strongText("Strong text\n"),
        Element::italicText("Itaic text\n"),
        Element::underlineText("Underline text\n"),
        Element::strikeText("Strike text\n"),
        Element::emphasizedText("Em text\n"),
        Element::code("Code text\n"),
        Element::link("This is link\n", 'https://github.com/chipslays/telegraph'),
        Element::space(), // add a new line otherwise the previous tags will be in heading
        new NodeElement('h3', [new NodeElement('a', 'This is link in title, wow!', ['href' => '#'])]),
        Element::space(), // add a new line otherwise the previous tags will be in blockquote
        Element::blockquote("Blockquote text\n"),
        Element::line(),
        Element::pre("echo 'Hello Pre Text!';"),
        Element::line(),
        Element::smallHeading("Items List"),
        Element::list(['Item 1', 'Item 2', 'Item 3']),
        Element::smallHeading("Numeric List"),
        Element::list(['Item 1', 'Item 2', 'Item 3'], true),
        Element::line(),
        Element::image('https://images.unsplash.com/photo-1541963463532-d68292c34b19?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxleHBsb3JlLWZlZWR8Mnx8fGVufDB8fHx8&w=1000&q=80', 'Image Caption'),
        // Element::image(File::upload('/path/to/local/image.jpg')),
        Element::line(),
        Element::smallHeading("Embed Blocks"),
        Element::youtube('https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'Youtube Caption'),
        Element::vimeo('https://vimeo.com/340057344', 'Vimeo Caption'),
        Element::twitter('https://twitter.com/elonmusk/status/1354174279894642703', 'GAMESTONK!!1!1'),
        // Element::embed('vendorName', 'https://example.com', 'Universal method for embed.'),
    ],
);

echo $page->getUrl(); // https://telegra.ph/Rich-Example-10-21
```

Upload files to Telegraph server.

```php
use Telegraph\File;

// Returns string
$imageUrl = File::upload('/path/to/local/image.jpg');

// Can pass url
$imageUrl = File::upload('https://example.com/image.jpg');

// Returns array with preserved keys
// ['my_nudes' => 'https://telegra.ph/*, ....]
$imageUrl = File::upload([
    'my_nudes' => '/path/to/local/image.jpg',
    'home_video_with_my_gf' => '/path/to/local/video.mp4',
]);
```

## Examples

You can found examples [here](/examples).

## Documentation

Not found.

So, `Client` class supports all methods of Telegraph API, just start typing e.g. `$client->createAccount(...)` and your IDE helps you.

Use the `Element` helper class to create rich content or use raw `NodeElement` for more flexible stuff.

`Element` uses `NodeElelemt` under the hood. See how it work [here](/src/Element.php).

## License

The MIT License (MIT). Please see [License File](/LICENSE.md) for more information.
