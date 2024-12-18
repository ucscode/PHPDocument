<?php

namespace Ucscode\PHPDocument\Parser\Enum;

enum NodeQueryRegexpEnum: string
{
    /**
     * Matches single or double quoted strings
     *
     * - A space in a CSS selector indicates a descendant combinator which matches nested elements
     * - A space within an attribute selector is part of the attribute value (like a string), not a combinator.
     */
    case EXPR_QUOTED_STRING = '/
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
     * Matches css attributes
     *
     * For best performance, match attributes whose value has been encoded
     */
    case EXPR_ATTRIBUTE = '/\[([^\]]+)\]/';
}
