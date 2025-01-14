<?php

namespace Ucscode\UssElement\Test\Node;

use PHPUnit\Framework\TestCase;
use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Enums\NodeNameEnum;
use Ucscode\UssElement\Node\ElementNode;
use Ucscode\UssElement\Test\Parser\Translator\HtmlLoaderTest;
use Ucscode\UssElement\Test\Traits\NodeHelperTrait;

class ElementNodeTest extends TestCase
{
    use NodeHelperTrait;

    public function testInitialValues(): void
    {
        $this->assertSame($this->getNodeBody()->getNodeName(), NodeNameEnum::NODE_BODY->value);
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
        $this->assertSame($this->getNodeA()->getParentElement(), $this->getNodeBody());

        $this->getNodeForm()->insertChildAtPosition(1, $this->getNodeImg());
        $this->assertCount(0, $this->getNodeA()->getChildNodes());
        $this->assertCount(4, $this->getNodeForm()->getChildNodes());

        $this->getNodeForm()->insertBefore($this->getNodeButton(), $this->getNodeInput());

        $this->assertSame($this->getNodeForm()->getFirstChild(), $this->getNodeButton());
        $this->assertSame($this->getNodeForm()->getLastChild(), $this->getNodeBr());
        $this->assertSame($this->getNodeForm()->getChildNodes()->get(2), $this->getNodeImg());

        $this->getNodeForm()->insertAfter($this->getNodeH1(), $this->getNodeButton());

        $this->assertSame($this->getNodeForm()->getChildNodes()->get(1), $this->getNodeH1());
        $this->assertSame($this->getNodeButton()->getNextSibling(), $this->getNodeH1());
        $this->assertSame($this->getNodeH1()->getPreviousSibling(), $this->getNodeButton());
        $this->assertCount(5, $this->getNodeForm()->getChildNodes());
        $this->assertCount(1, $this->getNodeDiv()->getChildNodes());
        $this->assertSame($this->getNodeForm(), $this->getNodeDiv()->getFirstChild());

        $this->assertTrue($this->getNodeForm()->hasChild($this->getNodeH1()));

        $selectNode = new ElementNode(NodeNameEnum::NODE_SELECT);

        $this->getNodeForm()->replaceChild($selectNode, $this->getNodeH1());

        $this->assertFalse($this->getNodeForm()->hasChild($this->getNodeH1()));
        $this->assertTrue($this->getNodeForm()->hasChild($selectNode));
        $this->assertCount(5, $this->getNodeForm()->getChildNodes());
        $this->assertSame($selectNode->getPreviousSibling(), $this->getNodeButton());

        $selectNode->moveToFirst();

        $this->assertSame($this->getNodeForm()->getFirstChild(), $selectNode);

        $selectNode->moveToLast();

        $this->assertSame($this->getNodeForm()->getLastChild(), $selectNode);

        $selectNode->moveToPosition(3);

        $this->assertSame($this->getNodeForm()->getChildNodes()->get(3), $selectNode);

        $selectNode->moveBefore($this->getNodeImg());

        $this->assertSame($selectNode->getNextSibling(), $this->getNodeImg());

        $selectNode->moveAfter($this->getNodeImg());

        $this->assertSame($selectNode->getPreviousSibling(), $this->getNodeImg());

        $this->getNodeForm()->sortChildNodes(function ($a, $b) {
            return strcmp($a->getNodeName(), $b->getNodeName());
        });

        $this->assertSame($this->getNodeForm()->getFirstChild(), $this->getNodeBr());
        $this->assertSame($this->getNodeForm()->getChild(1), $this->getNodeButton());
        $this->assertSame($this->getNodeForm()->getChild(2), $this->getNodeImg());
        $this->assertSame($this->getNodeForm()->getChild(3), $this->getNodeInput());
        $this->assertSame($this->getNodeForm()->getLastChild(), $selectNode);

        foreach ($this->getNodeForm()->getChildren() as $kid) {

        };
    }

    public function testElementAttributes(): void
    {
        $this->assertTrue($this->getNodeBody()->hasAttribute('id'));
        $this->getNodeBody()->getClassList()->add('super legis supreme');
        $this->assertStringContainsString('super', $this->getNodeBody()->getAttribute('class'));
        $this->assertCount(4, $this->getNodeBody()->getClassList());
        $this->getNodeBody()->setAttribute('class', 'puma');
        $this->assertCount(1, $this->getNodeBody()->getClassList());
        $this->getNodeBody()->setAttribute('class', null);
        $this->assertCount(0, $this->getNodeBody()->getClassList());
        $this->getNodeBody()->setAttribute('class', 'model-22_g and coperate');
        $this->assertCount(3, $this->getNodeBody()->getClassList());
    }

    public function testElementParser(): void
    {
        $collection = $this->getNodeBody()->getElementsByClassName('case-2');

        $this->assertCount(2, $collection);

        $collection = $this->getNodeBody()->getElementsByClassName('case-2 case-1');

        $this->assertCount(1, $collection);

        $collection = $this->getNodeBody()->getElementsByTagName('img');

        $this->assertCount(1, $collection);

        $collection = $this->getNodeBody()->getElementsByTagName('*');

        $this->assertCount(8, $collection);

        $collection = $this->getNodeBody()->querySelectorAll('*.case-1');

        $this->assertCount(3, $collection);

        $this->assertTrue($this->getNodeButton()->matches('.btn'));

        $nodeBr = $this->getNodeDiv()->querySelector('br');

        $this->assertSame($this->getNodeBr(), $nodeBr);
    }

    public function testSetInnerHTML(): void
    {
        $this->getNodeA()->setInnerHtml(HtmlLoaderTest::BOOTSTRAP_MODAL_STR);

        $this->assertCount(1, $this->getNodeA()->getChildNodes());

        /**
         * @var ElementInterface $modalElement
         */
        $modalElement = $this->getNodeA()->getFirstChild();

        $this->assertSame('modal', $modalElement->getAttribute('class'));
    }

    public function testElementVisibility(): void
    {
        $this->getNodeForm()->setVisible(false);
        $this->assertNotNull($this->getNodeDiv()->querySelector('form'));
        $this->assertStringNotContainsString('<form action', $this->getNodeDiv()->render());
    }

    public function testClearChildNodes(): void
    {
        $this->assertSame($this->getNodeButton()->getParentElement(), $this->getNodeForm());
        $this->getNodeForm()->clearChildNodes();
        $this->assertCount(0, $this->getNodeForm()->getChildNodes());
        $this->assertNull($this->getNodeButton()->getParentNode());
        $this->assertNull($this->getNodeButton()->getParentElement());
    }

    public function testCloneNode(): void
    {
        /**
         * @var ElementInterface
         */
        $divClone = $this->getNodeDiv()->cloneNode();

        $this->assertSame($divClone->getAttribute('class'), 'position-relative case-1');
        $this->assertSame($divClone->getAttribute('data-theme'), 'dark');
        $this->assertTrue($divClone->getChildren()->isEmpty());

        /**
         * @var ElementInterface
         */
        $divDeepClone = $this->getNodeDiv()->cloneNode(true);

        $this->assertFalse($divDeepClone->getChildren()->isEmpty());
        $this->assertNotSame($this->getNodeDiv(), $divDeepClone);
        $this->assertSame($this->getNodeDiv()->render(0), $divDeepClone->render(0));
        $this->assertSame($this->getNodeDiv()->getChildren()->count(), $divDeepClone->getChildren()->count());

        $textClone = $this->getNodeText()->cloneNode();

        $this->assertNotSame($this->getNodeText(), $textClone);
        $this->assertSame($this->getNodeText()->render(), $textClone->render());

        $bodyClone = $this->getNodeBody()->cloneNode(true);

        $this->assertNotSame($this->getNodeBody(), $bodyClone);
        $this->assertSame($this->getNodeBody()->render(), $bodyClone->render());
    }
}
