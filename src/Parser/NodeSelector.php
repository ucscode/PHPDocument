<?php

namespace Ucscode\PHPDocument\Parser;

use Ucscode\PHPDocument\Collection\HtmlCollection;
use Ucscode\PHPDocument\Contracts\NodeInterface;

class NodeSelector
{
    // Abstract Syntax Tree
    protected array $selectorAST;

    public function __construct(protected NodeInterface $node, protected string $selector)
    {

    }

    public function getResult(): HtmlCollection
    {
        return new HtmlCollection();
    }
}
