<?php

namespace Ucscode\PHPDocument\Test\Node;

use PHPUnit\Framework\TestCase;
use Ucscode\PHPDocument\Contracts\NodeInterface;
use Ucscode\PHPDocument\Enums\NodeEnum;
use Ucscode\PHPDocument\Node\ElementNode;
use Ucscode\PHPDocument\Test\Traits\NodeHelperTrait;

class ElementNodeTest extends TestCase
{
    use NodeHelperTrait;

    public function testInitialValues(): void
    {
        $this->assertSame($this->getNodeBody()->getNodeName(), NodeEnum::NODE_BODY->value);
        $this->assertTrue($this->getNodeImg()->isVoid());
        $this->assertFalse($this->getNodeButton()->isVoid());
        $this->assertCount(1, $this->getNodeBody()->getChildren());
        $this->assertNotNull($this->getNodeDiv()->getParentNode());
        $this->assertNotNull($this->getNodeDiv()->getParentElement());
        $this->assertSame($this->getNodeForm(), $this->getNodeInput()->getParentElement());
        $this->assertNull($this->getNodeBody()->getParentNode());
        $this->assertTrue($this->getNodeButton()->hasChild($this->getNodeText()));
        $this->assertSame($this->getNodeBr()->getPreviousSibling(), $this->getNodeInput());
        $this->assertSame($this->getNodeBr()->getNextSibling(), $this->getNodeButton());
        $this->assertNull($this->getNodeButton()->getNextSibling());
        $this->assertNull($this->getNodeH1()->getPreviousSibling());
        $this->assertSame($this->getNodeForm()->getFirstChild(), $this->getNodeInput());
        $this->assertSame($this->getNodeForm()->getLastChild(), $this->getNodeButton());
        $this->assertCount(3, $this->getNodeForm()->getChildNodes());
        $this->assertCount(1, $this->getNodeButton()->getChildNodes());
        $this->assertCount(0, $this->getNodeButton()->getChildren());
    }

    public function testArrangementLogic(): void
    {
        $this->getNodeBody()->prependChild($this->getNodeA());

        $this->assertCount(2, $this->getNodeBody()->getChildNodes());
        $this->assertCount(2, $this->getNodeDiv()->getChildNodes());
        $this->assertSame($this->getNodeBody()->getFirstChild(), $this->getNodeA());
        $this->assertSame($this->getNodeBody()->getLastChild(), $this->getNodeDiv());
        $this->assertSame($this->getNodeBody()->getChildNodes()->get(1), $this->getNodeDiv());
        $this->assertSame($this->getNodeA()->getFirstChild(), $this->getNodeImg());
        $this->assertSame($this->getNodeA()->getLastChild(), $this->getNodeImg());
        $this->assertCount(3, $this->getNodeForm()->getChildNodes());

        $this->getNodeForm()->insertChild(1, $this->getNodeImg());
        $this->assertCount(0, $this->getNodeA()->getChildNodes());
        $this->assertCount(4, $this->getNodeForm()->getChildNodes());

        $this->getNodeForm()->insertBefore($this->getNodeButton(), $this->getNodeInput());

        $this->assertSame($this->getNodeForm()->getFirstChild(), $this->getNodeButton());
        $this->assertSame($this->getNodeForm()->getLastChild(), $this->getNodeBr());
        $this->assertSame($this->getNodeForm()->getChildNodes()->get(2), $this->getNodeImg());

        $this->getNodeForm()->insertAfter($this->getNodeH1(), $this->getNodeButton());

        $this->assertSame($this->getNodeForm()->getChildNodes()->get(1), $this->getNodeH1());
        $this->assertCount(5, $this->getNodeForm()->getChildNodes());
        $this->assertCount(1, $this->getNodeDiv()->getChildNodes());

        $this->assertTrue($this->getNodeForm()->hasChild($this->getNodeH1()));

        $newNode = new ElementNode(NodeEnum::NODE_SELECT);

        $this->getNodeForm()->replaceChild($newNode, $this->getNodeH1());

        $this->assertFalse($this->getNodeForm()->hasChild($this->getNodeH1()));
        $this->assertTrue($this->getNodeForm()->hasChild($newNode));
        $this->assertCount(5, $this->getNodeForm()->getChildNodes());
        $this->assertSame($newNode->getPreviousSibling(), $this->getNodeButton());

        $newNode->moveToFirst();

        $this->assertSame($this->getNodeForm()->getFirstChild(), $newNode);

        $newNode->moveToLast();

        $this->assertSame($this->getNodeForm()->getLastChild(), $newNode);

        $newNode->moveToIndex(3);

        $this->assertSame($this->getNodeForm()->getChildNodes()->get(3), $newNode);

        $newNode->moveBefore($this->getNodeImg());

        var_dump(array_map(fn ($value) => $value->getNodeName(), $this->getNodeForm()->getChildNodes()->toArray()));

        $this->assertSame($newNode->getNextSibling(), $this->getNodeImg());

        $newNode->moveAfter($this->getNodeImg());

        var_dump(array_map(fn ($value) => $value->getNodeName(), $this->getNodeForm()->getChildNodes()->toArray()));

        $this->assertSame($newNode->getPreviousSibling(), $this->getNodeImg());
    }
}
