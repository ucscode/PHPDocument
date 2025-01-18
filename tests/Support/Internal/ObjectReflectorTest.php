<?php

namespace Ucscode\UssElement\Test\Support\Internal;

use PHPUnit\Framework\TestCase;
use Ucscode\UssElement\Collection\NodeList;
use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Enums\NodeNameEnum;
use Ucscode\UssElement\Exception\InvalidNodeException;
use Ucscode\UssElement\Node\ElementNode;
use Ucscode\UssElement\Support\ObjectReflector;

class ObjectReflectorTest extends TestCase
{
    public function testAccessToEncapsulatedPropertyAndMethod(): void
    {
        $nodeList = new NodeList([
            new ElementNode(NodeNameEnum::NODE_DIV)
        ]);

        $nodeListReflector = new ObjectReflector($nodeList);

        /**
         * @var NodeInterface[]
         */
        $items = $nodeListReflector->getProperty('items');

        $this->assertIsArray($items);
        $this->assertCount(1, $items);

        $this->expectException(InvalidNodeException::class);

        $nodeListReflector->invokeMethod('validateItem', 3);
    }
}
