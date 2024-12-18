<?php

namespace Ucscode\PHPDocument\Parser;

use Ucscode\PHPDocument\Parser\Codec\Transformer;
use Ucscode\PHPDocument\Parser\Enum\NodeQueryRegexpEnum;

/**
 * Selector Abstract Syntax Tree
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_selectors#terms
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/Attribute_selectors
 */
class SelectorAst
{
    public function __construct(protected string $selector)
    {

    }
}
