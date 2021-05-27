<?php

use Chipslays\Telegraph\Client;
use Chipslays\Telegraph\Types\Elements\Element;
use Chipslays\Telegraph\Types\Elements\NodeElement;

require __DIR__ . '/../vendor/autoload.php';

$client = new Client;

/** create new account */
$account = $client->createAccount('chipslays', 'Alex', 'https://github.com/chipslays');
$token = $account->getAccessToken(); // store this token in safe place for reuse

/** create new rich page */
$content = [
    Element::picture('https://telegram.org/file/811140775/1/Pc_4R_013Ow.144034/1c1eeaa592370d0b93', 'Wow, caption?'),

    Element::text('This a '), Element::link('chipslays/telegraph', 'https://github.com/chipslays/telegraph'), Element::text(' library for Telegraph API.'),

    Element::br(),

    Element::h3('Features'),
        Element::list([
            'Predefined Element Types;',
            'Upload files;',
            'Pretty easy to use;',
        ]),

    Element::br(),
    Element::hr(),
    Element::br(),

    Element::h4('Embed'),
        Element::youtube('https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'Caption'),
        Element::vimeo('https://vimeo.com/340057344', 'Caption'),
        Element::twitter('https://twitter.com/elonmusk/status/1354174279894642703', 'gamestonk!!1'),
        Element::embed('someHere', 'https://example.com', 'Universal method for embed.'),

    Element::hr(),

    Element::h4('Showcase'),
        Element::code([Element::link('Link in code? Easy.', 'https://github.com/chipslays')->toArray()]),
        Element::br(),
        Element::pre("<?php\n\n// pre code example\n\necho 'Hello world!';"),
        Element::hr(),
        Element::blockquote('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'),
        Element::br(),
        Element::em('Italic text'),
        Element::br(),
        Element::strong('Strong text'),
        Element::br(),
        Element::div('Paragraph'),
        Element::br(),
        Element::div('Div text'),
        Element::br(),
        Element::span('Span text'),
        Element::br(),
        Element::text('Inline text'),
        Element::br(),

        new NodeElement('p', ['from scratch...']),
        Element::new('p', ['from scratch...']), // alias for `new NodeElement()`

    Element::hr(),

    Element::h4('Lists'),
        Element::ul([Element::li(['from'])->toArray(), Element::li(['scratch'])->toArray()]),
        Element::br(),
        Element::list(['first', 'second'], true),
        Element::br(),
        Element::list(['first', [Element::link('with link', 'https://github.com')->toArray()]]),

    Element::hr(),

    Element::code('HAPPY CODING!'),
];

$page = $client->createPage($token, 'Rich page', $content);

print_r($page->toArray());

