# Telegraph PHP Library

Modern PHP library for Telegraph API. Telegraph is a minimalist anonymous publishing platform created by Telegram. Create beautiful articles in seconds without registration.

## Features

- 🚀 Simple & Intuitive API - Fluent interface for easy content building
- 🎨 Rich Content Support - Headers, paragraphs, lists, images, videos, code blocks
- 🔄 HTML Conversion - Automatic HTML to Telegraph format conversion with sanitization
- 🛡️ Type Safe - Full PHP 8.1+ type hints with readonly properties
- 📝 Fully Documented - Comprehensive PHPDoc comments for IDE support

## Requirements
- PHP 8.1 or higher
- cURL extension
- JSON extension

## Installation

Install via Composer:

```bash
composer require chipslays/telegraph
```

## Quick Start

```php
use Telegraph\Telegraph;

// Create client
$telegraph = new Telegraph;

// Create account
$account = $telegraph->createAccount(
    shortName: 'MyBlog',
    authorName: 'John Doe'
);

// IMPORTANT: Save this token!
$token = $account->accessToken();

// Create page
$page = $account->createPage(
    title: 'Hello World',
    content: 'My first Telegraph article!'
);

echo "Published: " . $page->url();
```

```php
$html = <<<HTML
    <h1>Article Title</h1>
    <p class="lead">Introduction with <strong>bold</strong> text.</p>
    <img src="https://example.com/image.jpg" alt="Image" />
    <p>Regular paragraph with <a href="https://example.com">link</a>.</p>
    <ul>
        <li>First item</li>
        <li>Second item</li>
    </ul>
    <blockquote>Important quote</blockquote>
HTML;

$page = $account->createPageFromHtml('Blog Post', $html);
```

## Usage Examples

You can find code examples in [examples folder](/examples) or in [EXAMPLES.md](/EXAMPLES.md).

## License

The MIT License (MIT). Please see [License File](/LICENSE.md) for more information.
