# Telegraph PHP Library - Examples

Complete guide with practical examples for all use cases.

## Table of Contents

- [Quick Start](#quick-start)
- [Account Management](#account-management)
- [Content Building](#content-building)
- [HTML Conversion](#html-conversion)
- [Page Management](#page-management)
- [Analytics](#analytics)
- [Error Handling](#error-handling)
- [Real-World Examples](#real-world-examples)
- [Integration Examples](#integration-examples)
- [Best Practices](#best-practices)

---

## Quick Start

### Create Your First Page

```php

<?php

use Telegraph\TelegraphClient;

$telegraph = new TelegraphClient();

// Create account
$account = $telegraph->createAccount('MyBlog', 'John Doe');
$token = $account->accessToken(); // SAVE THIS!

// Create simple page
$page = $account->createPage(
    title: 'My First Article',
    content: 'Hello Telegraph!'
);

echo $page->url(); // https://telegra.ph/My-First-Article-10-28
```

### Using Existing Account

```
$telegraph = new TelegraphClient('your_saved_token');
$account = $telegraph->account();

$page = $account->createPage('New Article', 'Content here');
```

---

## Account Management

### Managing Multiple Accounts

```php
class AccountManager
{
    private array $accounts = [];

    public function addAccount(string $name, string $token): void
    {
        $this->accounts[$name] = new TelegraphClient($token);
    }

    public function getAccount(string $name): TelegraphClient
    {
        return $this->accounts[$name] ?? throw new Exception("Account not found");
    }

    public function publishToAll(string $title, string $content): array
    {
        $urls = [];
        foreach ($this->accounts as $name => $client) {
            $page = $client->account()->createPage($title, $content);
            $urls[$name] = $page->url();
        }
        return $urls;
    }
}

// Usage
$manager = new AccountManager();
$manager->addAccount('tech', 'token1');
$manager->addAccount('blog', 'token2');

$urls = $manager->publishToAll('Article', 'Content');
```

### Updating Account Information

```php
$account->getInfo(['short_name', 'author_name', 'page_count']);

echo "Account: {$account->shortName()}\n";
echo "Pages: {$account->pageCount()}\n";

// Update account details
$account->edit(
    shortName: 'NewBlogName',
    authorName: 'Jane Smith',
    authorUrl: 'https://newsite.com'
);
```

### Token Management

```php
// Save token securely
function saveToken(string $token): void
{
    $encrypted = openssl_encrypt(
        $token,
        'aes-256-gcm',
        $_ENV['ENCRYPTION_KEY'],
        0,
        $iv,
        $tag
    );
    file_put_contents('token.enc', base64_encode($encrypted . '::' . $iv . '::' . $tag));
}

// Load token
function loadToken(): string
{
    $data = base64_decode(file_get_contents('token.enc'));
    [$encrypted, $iv, $tag] = explode('::', $data);
    return openssl_decrypt($encrypted, 'aes-256-gcm', $_ENV['ENCRYPTION_KEY'], 0, $iv, $tag);
}

// Revoke and get new token
$account->revokeToken();
$newToken = $account->accessToken();
saveToken($newToken);
```

---

## Content Building

### Complete Article Structure

```php
$content = $telegraph->content()
    // Title section
    ->h3('Complete Guide to Telegraph')
    ->p('Learn how to use Telegraph API effectively.')

    // Introduction
    ->h4('What is Telegraph?')
    ->p(function($b) {
        $b->text('Telegraph is a ')
          ->strong('minimalist')
          ->text(' publishing platform by ')
          ->link('Telegram', 'https://telegram.org')
          ->text('.');
    })

    // Features list
    ->h4('Key Features')
    ->ul([
        'No registration required',
        'Anonymous publishing',
        'Rich media support',
        'Fast and simple'
    ])

    // Step-by-step guide
    ->h4('Getting Started')
    ->ol([
        'Install the library',
        'Create an account',
        'Publish your first article',
        'Share the link'
    ])

    // Code example
    ->h4('Code Example')
    ->pre('<?php
$telegraph = new TelegraphClient();
$account = $telegraph->createAccount("Blog");
$page = $account->createPage("Title", "Content");
echo $page->url();')

    // Quote
    ->blockquote('Telegraph makes publishing simple and beautiful.')

    // Media
    ->h4('Example Image')
    ->figure(
        'https://telegra.ph/file/example.jpg',
        'Beautiful landscape photo'
    )

    // Conclusion
    ->hr()
    ->p(function($b) {
        $b->text('For more information, visit ')
          ->link('official docs', 'https://telegra.ph/api')
          ->text('.');
    })

    ->build();

$page = $account->createPage('Complete Guide', $content);
```

### Complex Inline Formatting

```php
$content = $telegraph->content()
    ->p(function($b) {
        $b->text('This paragraph contains ')
          ->strong('bold text')
          ->text(', ')
          ->em('italic text')
          ->text(', ')
          ->u('underlined text')
          ->text(', ')
          ->s('strikethrough text')
          ->text(', and ')
          ->code('inline code')
          ->text('.');
    })

    ->p(function($b) {
        $b->text('You can combine ')
          ->strong('bold and ')
          ->em('italic')
          ->text(' together, or add ')
          ->link('links', 'https://example.com')
          ->text(' anywhere.');
    })

    ->p(function($b) {
        $b->text('Multi-line text')
          ->br()
          ->text('with line breaks')
          ->br()
          ->text('works perfectly.');
    })

    ->build();
```

### Mixing Builders and Raw Arrays

```php
$content = $telegraph->content()
    ->h3('Mixed Content')

    // Use builder
    ->p('Builder paragraph')

    // Add raw node
    ->add([
        'tag' => 'aside',
        'children' => ['Custom aside element']
    ])

    // Continue with builder
    ->p('More content')

    ->build();
```

---

## HTML Conversion

### Convert Blog Post

```php
$html = '<article>
    <h1>Article Title</h1>
    <p class="lead">Introduction with <strong>bold</strong> text.</p>
    <img src="https://example.com/image.jpg" alt="Image" />
    <p>Regular paragraph with <a href="https://example.com">link</a>.</p>
    <ul>
        <li>First item</li>
        <li>Second item</li>
    </ul>
    <blockquote>Important quote</blockquote>
</article>';

$page = $account->createPageFromHtml('Blog Post', $html);
```

### WordPress to Telegraph

```php
function wordpressToTelegraph(WP_Post $post): string
{
    $telegraph = new TelegraphClient($_ENV['TELEGRAPH_TOKEN']);
    $account = $telegraph->account();

    // Get post content
    $html = apply_filters('the_content', $post->post_content);

    // Convert and publish
    $page = $account->createPageFromHtml(
        title: $post->post_title,
        html: $html,
        authorName: get_the_author_meta('display_name', $post->post_author)
    );

    return $page->url();
}
```

### Medium to Telegraph

```php
function mediumToTelegraph(string $mediumUrl): string
{
    // Fetch Medium article
    $html = file_get_contents($mediumUrl);

    // Extract title
    preg_match('/<h1.*?>(.*?)<\/h1>/', $html, $matches);
    $title = strip_tags($matches ?? 'Article');[^1]

    // Extract content
    preg_match('/<article.*?>(.*?)<\/article>/s', $html, $matches);
    $content = $matches ?? '';[^1]

    // Publish to Telegraph
    $telegraph = new TelegraphClient($_ENV['TELEGRAPH_TOKEN']);
    $page = $telegraph->account()->createPageFromHtml($title, $content);

    return $page->url();
}

```

---

## Page Management

### Edit Page with Validation

```php

function updatePage(string $path, string $title, string $content): bool
{
    try {
        $telegraph = new TelegraphClient($_ENV['TELEGRAPH_TOKEN']);
        $page = $telegraph->page($path);

        // Check if we can edit
        $page->get();
        if (!$page->canEdit()) {
            throw new Exception('No permission to edit this page');
        }

        // Validate content size (64KB limit)
        if (strlen(json_encode($content)) > 65536) {
            throw new Exception('Content too large');
        }

        // Update
        $page->edit($title, $content);
        return true;

    } catch (Exception $e) {
        error_log("Telegraph error: " . $e->getMessage());
        return false;
    }
}
```

### Clone Page

```php

function clonePage(string $sourcePath): Page
{
    $telegraph = new TelegraphClient($_ENV['TELEGRAPH_TOKEN']);
    $account = $telegraph->account();

    // Get source page
    $source = $telegraph->page($sourcePath);
    $source->get(returnContent: true);

    // Create copy
    return $account->createPage(
        title: $source->title() . ' (Copy)',
        content: $source->content(),
        authorName: $source->authorName(),
        authorUrl: $source->authorUrl()
    );
}

```

### Bulk Update Pages

```php

function bulkUpdateAuthor(string $newAuthorName): void
{
    $telegraph = new TelegraphClient($_ENV['TELEGRAPH_TOKEN']);
    $account = $telegraph->account();

    $pages = $account->pages(limit: 200);

    foreach ($pages as $page) {
        $page->get(returnContent: true);
        $page->edit(
            title: $page->title(),
            content: $page->content(),
            authorName: $newAuthorName
        );

        // Rate limiting
        usleep(100000); // 100ms delay
    }
}
```

---

## Analytics

### Track Page Views

```php

function getPageStats(string $path): array
{
    $telegraph = new TelegraphClient();
    $page = $telegraph->page($path);

    return [
        'total' => $page->views()->views,
        'today' => $page->views(
            year: (int)date('Y'),
            month: (int)date('m'),
            day: (int)date('d')
        )->views,
        'this_month' => $page->views(
            year: (int)date('Y'),
            month: (int)date('m')
        )->views,
        'this_year' => $page->views(
            year: (int)date('Y')
        )->views,
    ];
}

$stats = getPageStats('My-Article-10-28');
print_r($stats);

```

### Analytics Dashboard

```php

class TelegraphAnalytics
{
private TelegraphClient $client;

    public function __construct(string $token)
    {
        $this->client = new TelegraphClient($token);
    }

    public function getDashboard(): array
    {
        $account = $this->client->account();
        $account->getInfo(['page_count']);

        $pages = $account->pages(limit: 200);
        $totalViews = 0;
        $topPages = [];

        foreach ($pages as $page) {
            $views = $page->viewsCount();
            $totalViews += $views;

            $topPages[] = [
                'title' => $page->title(),
                'url' => $page->url(),
                'views' => $views
            ];
        }

        // Sort by views
        usort($topPages, fn($a, $b) => $b['views'] <=> $a['views']);

        return [
            'total_pages' => $account->pageCount(),
            'total_views' => $totalViews,
            'avg_views' => round($totalViews / count($pages)),
            'top_pages' => array_slice($topPages, 0, 10)
        ];
    }
    }

$analytics = new TelegraphAnalytics($_ENV['TELEGRAPH_TOKEN']);
$dashboard = $analytics->getDashboard();

```

### Export Analytics to CSV

```php

function exportAnalytics(string $filename): void
{
    $telegraph = new TelegraphClient($_ENV['TELEGRAPH_TOKEN']);
    $pages = $telegraph->account()->pages(limit: 200);

    $fp = fopen($filename, 'w');
    fputcsv($fp, ['Title', 'URL', 'Views', 'Path']);

    foreach ($pages as $page) {
        fputcsv($fp, [
            $page->title(),
            $page->url(),
            $page->viewsCount(),
            $page->path()
        ]);
    }

    fclose($fp);
}

exportAnalytics('telegraph_stats.csv');
```

---

## Error Handling

### Comprehensive Error Handling

```php
use Telegraph\Exceptions\TelegraphException;

function publishWithRetry(string $title, string $content, int $maxRetries = 3): ?Page
{
    $telegraph = new TelegraphClient($_ENV['TELEGRAPH_TOKEN']);
    $account = $telegraph->account();

    for ($i = 0; $i < $maxRetries; $i++) {
        try {
            return $account->createPage($title, $content);

        } catch (TelegraphException $e) {
            $msg = $e->getMessage();

            if (str_contains($msg, 'ACCESS_TOKEN_INVALID')) {
                // Token expired
                error_log("Token invalid");
                return null;
            }

            if (str_contains($msg, 'CONTENT_TOO_BIG')) {
                // Content too large, truncate
                $content = substr($content, 0, 60000);
                continue;
            }

            if (str_contains($msg, 'FLOOD_WAIT')) {
                // Rate limit, wait and retry
                sleep(5);
                continue;
            }

            error_log("Telegraph error: $msg");
            return null;
        }
    }

    return null;
}

```

### Validation Before Publishing

```php

class ContentValidator
{
    public function validate(string $title, $content): array
    {
    $errors = [];

        // Title validation
        if (strlen($title) < 1 || strlen($title) > 256) {
            $errors[] = 'Title must be 1-256 characters';
        }

        // Content validation
        if (is_string($content)) {
            if (empty(trim($content))) {
                $errors[] = 'Content cannot be empty';
            }
        } elseif (is_array($content)) {
            if (empty($content)) {
                $errors[] = 'Content cannot be empty';
            }

            $size = strlen(json_encode($content));
            if ($size > 65536) {
                $errors[] = "Content too large: {$size} bytes (max 64KB)";
            }
        }

        return $errors;
    }
}

// Usage
$validator = new ContentValidator();
$errors = $validator->validate($title, $content);

if (empty($errors)) {
    $page = $account->createPage($title, $content);
} else {
    foreach ($errors as $error) {
        echo "Error: $error\n";
    }
}
```

---

## Real-World Examples

### Newsletter Builder

```php

class NewsletterBuilder
{
    private TelegraphClient $telegraph;

    public function __construct(string $token)
    {
        $this->telegraph = new TelegraphClient($token);
    }

    public function createNewsletter(array $articles): Page
    {
        $content = $this->telegraph->content()
            ->h3('Weekly Newsletter')
            ->p('Here are this week\'s top articles:')
            ->hr();

        foreach ($articles as $article) {
            $content
                ->h4($article['title'])
                ->p($article['summary'])
                ->p(function($b) use ($article) {
                    $b->link('Read more →', $article['url']);
                })
                ->hr();
        }

        $content->p('Thanks for reading!');

        return $this->telegraph->account()->createPage(
            title: 'Newsletter - ' . date('F j, Y'),
            content: $content->build()
        );
    }
}

// Usage
$newsletter = new NewsletterBuilder($_ENV['TELEGRAPH_TOKEN']);
$page = $newsletter->createNewsletter([
    [
        'title' => 'Article 1',
        'summary' => 'Summary text...',
        'url' => 'https://example.com/article1'
    ],
    [
        'title' => 'Article 2',
        'summary' => 'Summary text...',
        'url' => 'https://example.com/article2'
    ]
]);

```

### Product Catalog

```php

class ProductCatalog
{
    public function createCatalogPage(array $products): Page
    {
        $telegraph = new TelegraphClient($_ENV['TELEGRAPH_TOKEN']);

        $content = $telegraph->content()
            ->h3('Product Catalog')
            ->p('Browse our latest products:');

        foreach ($products as $product) {
            $content
                ->h4($product['name'])
                ->figure($product['image'], $product['name'])
                ->p($product['description'])
                ->p(function($b) use ($product) {
                    $b->strong("Price: $" . $product['price'])
                        ->br()
                        ->link('Buy Now', $product['buy_url']);
                })
                ->hr();
        }

        return $telegraph->account()->createPage(
            title: 'Product Catalog',
            content: $content->build()
        );
    }
}
```

### Documentation Generator

```php

class DocsGenerator
{
    public function generateApiDocs(array $endpoints): Page
    {
        $telegraph = new TelegraphClient($_ENV['TELEGRAPH_TOKEN']);

        $content = $telegraph->content()
            ->h3('API Documentation')
            ->p('Complete API reference for developers.');

        foreach ($endpoints as $endpoint) {
            $content
                ->h4($endpoint['method'] . ' ' . $endpoint['path'])
                ->p($endpoint['description'])
                ->p(function($b) {
                    $b->strong('Parameters:');
                })
                ->ul($endpoint['params'])
                ->p(function($b) {
                    $b->strong('Example Request:');
                })
                ->pre($endpoint['example'])
                ->hr();
        }

        return $telegraph->account()->createPage(
            title: 'API Documentation',
            content: $content->build()
        );
    }
}

```

---

## Integration Examples

### Laravel Service Provider

```php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Telegraph\TelegraphClient;

class TelegraphServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TelegraphClient::class, function ($app) {
            return new TelegraphClient(config('services.telegraph.token'));
        });
    }
}

// Usage in controller
class ArticleController extends Controller
{
    public function __construct(
        private TelegraphClient $telegraph
    ) {}

    public function publish(Request $request): JsonResponse
    {
        $page = $this->telegraph->account()->createPage(
            $request->input('title'),
            $request->input('content')
        );

        return response()->json(['url' => $page->url()]);
    }
}

```

### Symfony Service

```yaml


# services.yaml

services:
Telegraph\TelegraphClient:
arguments:
$accessToken: '%env(TELEGRAPH_TOKEN)%'

```

```php

// Controller
class PublishController extends AbstractController
{
    public function __construct(
        private TelegraphClient $telegraph
    ) {}

    #[Route('/publish', methods: ['POST'])]
    public function publish(Request $request): JsonResponse
    {
        $page = $this->telegraph->account()->createPage(
            $request->request->get('title'),
            $request->request->get('content')
        );

        return $this->json(['url' => $page->url()]);
    }
}

```

### WordPress Plugin

```php

add_action('publish_post', 'publish_to_telegraph', 10, 2);

function publish_to_telegraph($post_id, $post)
{
    if ($post->post_status !== 'publish') {
        return;
    }

    $telegraph = new Telegraph\TelegraphClient(get_option('telegraph_token'));
    $html = apply_filters('the_content', $post->post_content);

    try {
        $page = $telegraph->account()->createPageFromHtml(
            $post->post_title,
            $html,
            get_the_author_meta('display_name', $post->post_author)
        );

        update_post_meta($post_id, 'telegraph_url', $page->url());

    } catch (Exception $e) {
        error_log('Telegraph publish failed: ' . $e->getMessage());
    }
}

```

---

## Best Practices

### Token Storage

```php

// ❌ BAD: Hardcoded token
$telegraph = new TelegraphClient('13a7d37c495d339a2002454f7bfd2d32eb205d5f29c3b7dc3bf5d8ba15d0');

// ✅ GOOD: Environment variable
$telegraph = new TelegraphClient($_ENV['TELEGRAPH_TOKEN']);

// ✅ GOOD: From database
$token = $userRepository->getTelegraphToken($userId);
$telegraph = new TelegraphClient($token);

```

### Content Validation

```php

// ✅ Always validate before publishing
$errors = $validator->validate($title, $content);
if (!empty($errors)) {
    throw new ValidationException(implode(', ', $errors));
}

// ✅ Check content size
$size = strlen(json_encode($content));
if ($size > 65536) {
    throw new Exception("Content too large: $size bytes");
}

```

### Rate Limiting

```php

// ✅ Add delays between bulk operations
foreach ($items as $item) {
    $page = $account->createPage($item['title'], $item['content']);
    usleep(100000); // 100ms delay
}

```

### Error Logging

```php

// ✅ Log all errors
try {
    $page = $account->createPage($title, $content);
} catch (TelegraphException $e) {
    error_log("[Telegraph] Error: " . $e->getMessage());
    error_log("[Telegraph] Title: $title");
    throw $e;
}

```

---

## Troubleshooting

### Common Issues

**Issue: ACCESS_TOKEN_INVALID**
```php

// Solution: Check token validity
try {
    $account->getInfo();
} catch (TelegraphException $e) {
    if (str_contains($e->getMessage(), 'ACCESS_TOKEN_INVALID')) {
        // Create new account
        $account = $telegraph->createAccount('NewAccount');
        saveToken($account->accessToken());
    }
}

```

**Issue: CONTENT_TOO_BIG**
```php

// Solution: Split into multiple pages
if (strlen(json_encode($content)) > 65536) {
    $chunks = array_chunk($content, 50);
    foreach ($chunks as $i => $chunk) {
        $account->createPage("Article Part " . ($i + 1), $chunk);
    }
}

```

---

For more information, see the [main README](/README.md) and [official Telegraph API documentation](https://telegra.ph/api).