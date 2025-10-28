<?php

use Telegraph\TelegraphClient;

require __DIR__ . '/../vendor/autoload.php';

$telegraph = new TelegraphClient();

$account = $telegraph->createAccount(
    shortName: 'TechBlog',
    authorName: 'John Doe',
    authorUrl: 'https://example.com'
);

$page = $account->createPage(
    title: 'Ultimate Telegraph Feature Showcase',
    content: $telegraph->content()
        // Hero section with complex formatting
        ->p(function($b) {
            $b->text('Welcome to the ')
              ->strong('complete guide')
              ->text(' on using Telegraph PHP Library. This article demonstrates ')
              ->em('every single feature')
              ->text(' available in the API.');
        })

        ->blockquote('This library provides a type-safe, modern PHP 8.1+ interface for Telegraph API with full IDE support and comprehensive documentation.')

        ->hr()

        // Table of contents simulation
        ->h3('📋 Table of Contents')
        ->ol([
            'Text Formatting & Inline Elements',
            'Headers & Structure',
            'Lists & Nested Content',
            'Code Examples',
            'Media & Embeds',
            'Advanced Techniques'
        ])

        ->hr()

        // Section 1: Text Formatting
        ->h3('1️⃣ Text Formatting & Inline Elements')
        ->p(function($b) {
            $b->text('Telegraph supports ')
              ->strong('bold')
              ->text(', ')
              ->em('italic')
              ->text(', ')
              ->u('underlined')
              ->text(', ')
              ->s('strikethrough')
              ->text(', and ')
              ->code('inline code')
              ->text(' formatting.');
        })

        ->p(function($b) {
            $b->text('You can ')
              ->link('create links', 'https://telegra.ph/api')
              ->text(' to any URL, including ')
              ->link('GitHub repositories', 'https://github.com/chipslays/telegraph')
              ->text('.');
        })

        ->p(function($b) {
            $b->text('Line breaks can be inserted')
              ->br()
              ->text('anywhere in the text')
              ->br()
              ->text('to create multi-line paragraphs.')
              ->br()
              ->text('Perfect for poetry or addresses!');
        })

        ->aside('💡 Pro tip: Use callbacks with InlineBuilder for complex inline formatting. This keeps your code clean and readable!')

        // Section 2: Headers
        ->hr()
        ->h3('2️⃣ Headers & Document Structure')
        ->p('Telegraph supports two levels of headers for organizing your content:')

        ->h4('Major Section (H3)')
        ->p('Use h3() for main sections of your article.')

        ->h4('Subsection (H4)')
        ->p('Use h4() for subsections and nested topics.')

        // Section 3: Lists
        ->hr()
        ->h3('3️⃣ Lists & Nested Content')

        ->h4('Unordered Lists')
        ->p('Perfect for features, benefits, or non-sequential items:')
        ->ul([
            'Type-safe PHP 8.1+ implementation',
            'Fluent interface for easy content building',
            'Comprehensive PHPDoc documentation',
            'Readonly properties for immutability',
            'Full Telegraph API coverage'
        ])

        ->h4('Ordered Lists')
        ->p('Great for step-by-step instructions:')
        ->ol([
            'Install via Composer: composer require chipslays/telegraph',
            'Create TelegraphClient instance',
            'Create or load account',
            'Build content using fluent interface',
            'Publish your page and get URL'
        ])

        ->h4('Mixed Content in Lists')
        ->p('Lists can contain complex formatted items:')
        ->ul([
            'First item with plain text',
            'Second item',
            'Third item'
        ])

        // Section 4: Code
        ->hr()
        ->h3('4️⃣ Code Examples')

        ->p(function($b) {
            $b->text('For inline code snippets, use ')
              ->code('$variable->method()')
              ->text(' notation.');
        })

        ->p('For larger code blocks, use pre-formatted text:')

        ->pre('<?php

use Telegraph\TelegraphClient;

$telegraph = new TelegraphClient();
$account = $telegraph->createAccount(
    shortName: \'MyBlog\',
    authorName: \'Author Name\'
);

$page = $account->createPage(
    title: \'My Article\',
    content: $telegraph->content()
        ->h3(\'Title\')
        ->p(\'Content here\')
        ->build()
);

echo $page->url();')

        ->aside('📝 Code blocks preserve all whitespace and formatting, making them perfect for sharing code snippets.')

        // Section 5: Media
        ->hr()
        ->h3('5️⃣ Media & Embeds')

        ->h4('Images')
        ->p('Simple images without captions:')
        ->img('https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800')

        ->p('Images with descriptive captions:')
        ->figure(
            'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800',
            'A programmer working on code - perfect example of modern development workflow'
        )

        ->h4('Video Embeds')
        ->p('Embed videos from popular platforms:')

        ->youtube('https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'Youtube Caption')

        ->vimeo('https://vimeo.com/340057344', 'Vimeo Caption')

        ->twitter('https://twitter.com/elonmusk/status/1354174279894642703', 'GAMESTONK!!1!1')

        // Section 6: Advanced
        ->hr()
        ->h3('6️⃣ Advanced Techniques')

        ->h4('Complex Inline Formatting')
        ->p(function($b) {
            $b->text('You can ')
              ->strong('nest')
              ->text(' ')
              ->em('multiple')
              ->text(' ')
              ->u('formatting')
              ->text(' ')
              ->code('styles')
              ->text(' in creative ways: ')
              ->strong('bold with ')
              ->link('link inside', 'https://example.com')
              ->text('!');
        })

        ->h4('Quotes & Citations')
        ->blockquote('The best way to predict the future is to invent it.')
        ->p(function($b) {
            $b->text('— ')
              ->em('Alan Kay')
              ->text(', Computer Scientist');
        })

        ->h4('Separation & Visual Breaks')
        ->p('Use horizontal rules to separate major sections:')
        ->hr()
        ->p('This creates a clear visual break in your content.')
        ->hr()

        // Conclusion
        ->h3('🎉 Conclusion')
        ->p(function($b) {
            $b->text('This library provides a ')
              ->strong('complete, type-safe interface')
              ->text(' to Telegraph API. With ')
              ->em('fluent chaining')
              ->text(', ')
              ->code('inline callbacks')
              ->text(', and ')
              ->u('comprehensive documentation')
              ->text(', creating beautiful Telegraph articles has never been easier!');
        })

        ->blockquote('Built with ❤️ using PHP 8.1+')

        ->hr()

        ->h4('📚 Resources')
        ->ul([
            'Official Telegraph API: https://telegra.ph/api',
            'Library GitHub: https://github.com/chipslays/telegraph',
            'PHP Documentation: https://www.php.net',
            'Composer Package: packagist.org/packages/chipslays/telegraph'
        ])

        ->p(function($b) {
            $b->text('Made with ')
              ->strong('Telegraph PHP Library')
              ->text(' • ')
              ->link('View on GitHub', 'https://github.com/chipslays/telegraph');
        })

        ->build()
);

echo "✅ Page published successfully!\n";
echo "🔗 URL: " . $page->url() . "\n";
echo "📊 Views: " . $page->viewsCount() . "\n";
