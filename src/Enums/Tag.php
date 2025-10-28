<?php

declare(strict_types=1);

namespace Telegraph\Enums;

/**
 * Telegraph Supported HTML Tags
 *
 * Enumeration of all HTML tags supported by Telegraph API.
 * These are the ONLY tags that Telegraph will accept and render.
 * Any other tags will be filtered out by HtmlConverter.
 *
 * Use these enum cases in NodeBuilder for type-safe tag specification,
 * or use string values directly in ContentBuilder methods.
 *
 * @see https://telegra.ph/api Content format documentation
 */
enum Tag: string
{
    /** Anchor/link element - requires href attribute */
    case A = 'a';

    /** Aside element for tangentially related content */
    case ASIDE = 'aside';

    /** Bold text (stylistic, non-semantic) */
    case B = 'b';

    /** Block quotation element */
    case BLOCKQUOTE = 'blockquote';

    /** Line break (void element) */
    case BR = 'br';

    /** Inline code element */
    case CODE = 'code';

    /** Emphasized text (semantic italic) */
    case EM = 'em';

    /** Figure caption element (must be inside figure) */
    case FIGCAPTION = 'figcaption';

    /** Figure container for images with captions */
    case FIGURE = 'figure';

    /** Level 3 heading (h1/h2 convert to this) */
    case H3 = 'h3';

    /** Level 4 heading (h5/h6 convert to this) */
    case H4 = 'h4';

    /** Horizontal rule separator (void element) */
    case HR = 'hr';

    /** Italic text (stylistic, non-semantic) */
    case I = 'i';

    /** Iframe for embedded content - requires src attribute */
    case IFRAME = 'iframe';

    /** Image element - requires src attribute (void element) */
    case IMG = 'img';

    /** List item (must be inside ul or ol) */
    case LI = 'li';

    /** Ordered (numbered) list container */
    case OL = 'ol';

    /** Paragraph element */
    case P = 'p';

    /** Preformatted text/code block */
    case PRE = 'pre';

    /** Strikethrough text */
    case S = 's';

    /** Strong emphasis text (semantic bold) */
    case STRONG = 'strong';

    /** Underlined text */
    case U = 'u';

    /** Unordered (bulleted) list container */
    case UL = 'ul';

    /** Video element - requires src attribute */
    case VIDEO = 'video';
}
