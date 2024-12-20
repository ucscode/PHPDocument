<?php

namespace Ucscode\UssElement\Parser\Enum;

/**
 * An enum containing a list of regular expressions used by several parsers
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
enum NodeQueryRegexpEnum: string
{
    /**
     * Matches single or double quoted strings
     *
     * - A space in a CSS selector indicates a descendant combinator which matches nested elements
     * - A space within an attribute selector is part of the attribute value (like a string), not a combinator.
     */
    case REGEXP_QUOTED_STRING = '/
        (["\'])          # Capture opening quote (single or double)
        (                # Start capturing the content
            (?:          # Non-capturing group
                \\\\ \\1 |   # Match escaped quotes (\' or \")
                \\\\ |       # Match escaped backslashes
                .         # Match any other character
            )*?          # Do not be greedy
        )
        \\1              # Match the corresponding closing quote
    /x';

    /**
     * Matches attributes in selector
     *
     * For best performance, encode attributes
     */
    case REGEXP_ATTRIBUTES = '/(?<!\()\[([^\]]+)\]/';

    /**
     * Match classes in selector
     */
    case REGEXP_CLASSES = '/(?<!\()\.([a-z0-9_-]+)/i';

    /**
     * Match nodename in selector
     */
    case REGEXP_TAG = '/^(?:[a-z]+[a-z0-9-]*)|\*/i';

    /**
     * Match id in selector
     */
    case REGEXP_ID = '/#([a-z0-9_-]+)/i';

    /**
     * Match :pseudo-classes in selector
     */
    case REGEXP_PSEUDO_CLASSES = '/(?<!:):([a-z-]+)(?!\()/i';

    /**
     * Match :pseudo-functions() in selector
     */
    case REGEXP_PSEUDO_FUNCTIONS = '/:([a-z-]+)\(([^\)]+)\)/i';

    /**
     * Match ::pseudo-elements in selector
     */
    case REGEXP_PSEUDO_ELEMENTS = '/::([a-z-]+)/i';
}
