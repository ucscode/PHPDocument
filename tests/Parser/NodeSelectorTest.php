<?php

namespace Ucscode\PHPDocument\Test\Parser;

use PHPUnit\Framework\TestCase;
use Ucscode\PHPDocument\Parser\NodeSelector;
use Ucscode\PHPDocument\Test\Traits\NodeHelperTrait;

class NodeSelectorTest extends TestCase
{
    use NodeHelperTrait;

    public function testNodeSelectorCase1(): void
    {
        $collection = (new NodeSelector($this->getNodeBody(), 'body .case-1, div .case-1, div form .btn'))->getResult();

        var_dump(array_map(fn ($node) => $node->getNodeName(), $collection->toArray()));

        $this->assertCount(4, $collection);
    }
}
