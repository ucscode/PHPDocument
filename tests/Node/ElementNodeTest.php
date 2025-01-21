<?php

namespace Ucscode\UssElement\Test\Node;

use PHPUnit\Framework\TestCase;
use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Enums\NodeNameEnum;
use Ucscode\UssElement\Enums\NodeTypeEnum;
use Ucscode\UssElement\Exception\DOMException;
use Ucscode\UssElement\Node\ElementNode;
use Ucscode\UssElement\Test\Parser\Translator\HtmlLoaderTest;
use Ucscode\UssElement\Test\Traits\NodeHelperTrait;

class ElementNodeTest extends TestCase
{
    use NodeHelperTrait;

    public function testNodeCounts(): void
    {
        $this->assertCount(1, $this->getBodyNode()->getChildNodes());
        $this->assertCount(3, $this->getDivNode()->getChildNodes());
        $this->assertCount(1, $this->getANode()->getChildNodes());
        $this->assertCount(3, $this->getFormNode()->getChildNodes());
        $this->assertCount(1, $this->getButtonNode()->getChildNodes());
        $this->assertCount(0, $this->getButtonNode()->getChildren());
    }

    public function testNodeRelationship(): void
    {
        $this->assertSame($this->getBodyNode()->getNodeName(), NodeNameEnum::NODE_BODY->value);
        $this->assertTrue($this->getImgNode()->isVoid());
        $this->assertFalse($this->getButtonNode()->isVoid());
        $this->assertNotNull($this->getDivNode()->getParentNode());
        $this->assertNotNull($this->getDivNode()->getParentElement());
        $this->assertSame($this->getFormNode(), $this->getInputNode()->getParentElement());
        $this->assertNull($this->getBodyNode()->getParentNode());
        $this->assertTrue($this->getButtonNode()->hasChild($this->getTextNode()));
        // $this->assertSame($this->getNodeBr()->getPreviousSibling(), $this->getNodeInput());
        // $this->assertSame($this->getNodeBr()->getNextSibling(), $this->getNodeButton());
        // $this->assertNull($this->getNodeButton()->getNextSibling());
        // $this->assertNull($this->getNodeH1()->getPreviousSibling());
        $this->assertSame($this->getFormNode()->getFirstChild(), $this->getInputNode());
        $this->assertSame($this->getFormNode()->getLastChild(), $this->getButtonNode());
    }

    public function testNodeManipulation(): void
    {
        // Modification 1
        $this->getBodyNode()->prependChild($this->getANode());

        $this->assertCount(2, $this->getBodyNode()->getChildNodes());
        $this->assertCount(2, $this->getDivNode()->getChildNodes());

        $this->assertSame($this->getBodyNode()->getFirstChild(), $this->getANode());
        $this->assertSame($this->getBodyNode()->getLastChild(), $this->getDivNode());
        $this->assertSame($this->getBodyNode()->getChildNodes()->get(1), $this->getDivNode());
        $this->assertSame($this->getANode()->getFirstChild(), $this->getImgNode());
        $this->assertSame($this->getANode()->getLastChild(), $this->getImgNode());
        $this->assertSame($this->getANode()->getParentElement(), $this->getBodyNode());

        $this->assertSame($this->getImgNode()->getParentElement(), $this->getANode());

        // Modification 2
        $this->getFormNode()->insertChildAtPosition(1, $this->getImgNode());
        
        $this->assertCount(0, $this->getANode()->getChildNodes());
        $this->assertCount(4, $this->getFormNode()->getChildNodes());
        $this->assertSame($this->getImgNode(), $this->getFormNode()->getChild(1));
        $this->assertNotNull($this->getImgNode()->getParentElement());
        $this->assertSame($this->getImgNode()->getParentElement(), $this->getFormNode());

        // Modification 3
        $this->getFormNode()->insertBefore($this->getButtonNode(), $this->getInputNode());

        $this->assertCount(4, $this->getFormNode()->getChildNodes());
        $this->assertSame($this->getFormNode()->getFirstChild(), $this->getButtonNode());
        $this->assertSame($this->getFormNode()->getLastChild(), $this->getBrNode());
        $this->assertSame($this->getFormNode()->getChildNodes()->get(2), $this->getImgNode());

        // Modification 4
        $this->getFormNode()->insertAfter($this->getH1Node(), $this->getButtonNode());

        $this->assertSame($this->getFormNode()->getChild(1), $this->getH1Node());
        $this->assertSame($this->getFormNode()->getChildNodes()->get(1), $this->getH1Node());
        $this->assertSame($this->getButtonNode()->getNextSibling(), $this->getH1Node());
        $this->assertSame($this->getH1Node()->getPreviousSibling(), $this->getButtonNode());
        $this->assertCount(5, $this->getFormNode()->getChildNodes());
        $this->assertCount(1, $this->getDivNode()->getChildNodes());
        $this->assertSame($this->getFormNode(), $this->getDivNode()->getFirstChild());
        $this->assertTrue($this->getFormNode()->hasChild($this->getH1Node()));

        // Modification 5
        $selectNode = new ElementNode(NodeNameEnum::NODE_SELECT);

        $this->getFormNode()->replaceChild($selectNode, $this->getH1Node());

        $this->assertFalse($this->getFormNode()->hasChild($this->getH1Node()));
        $this->assertTrue($this->getFormNode()->hasChild($selectNode));
        $this->assertCount(5, $this->getFormNode()->getChildNodes());
        $this->assertSame($selectNode->getPreviousSibling(), $this->getButtonNode());

        // Modification 6
        $selectNode->moveToFirstSibling();

        $this->assertSame($this->getFormNode()->getFirstChild(), $selectNode);

        // Modification 7
        $selectNode->moveToLastSibling();

        $this->assertSame($this->getFormNode()->getLastChild(), $selectNode);

        // Modification 8
        $selectNode->moveToSiblingPosition(3);

        $this->assertSame($this->getFormNode()->getChildNodes()->get(3), $selectNode);

        // Modification 9
        $selectNode->moveBeforeSibling($this->getImgNode());

        $this->assertSame($selectNode->getNextSibling(), $this->getImgNode());

        // Modification 10
        $selectNode->moveAfterSibling($this->getImgNode());
        
        $this->assertSame($selectNode->getPreviousSibling(), $this->getImgNode());

        // Modification 11
        $this->getFormNode()->sortChildNodes(function ($a, $b) {
            return strcmp($a->getNodeName(), $b->getNodeName());
        });

        $this->assertSame($this->getFormNode()->getFirstChild(), $this->getBrNode());
        $this->assertSame($this->getFormNode()->getChild(1), $this->getButtonNode());
        $this->assertSame($this->getFormNode()->getChild(2), $this->getImgNode());
        $this->assertSame($this->getFormNode()->getChild(3), $this->getInputNode());
        $this->assertSame($this->getFormNode()->getLastChild(), $selectNode);
    }

    public function testElementAttributes(): void
    {
        $this->assertTrue($this->getBodyNode()->hasAttribute('id'));
        $this->getBodyNode()->getClassList()->add('super legis supreme');
        $this->assertStringContainsString('super', $this->getBodyNode()->getAttribute('class'));
        $this->assertCount(4, $this->getBodyNode()->getClassList());
        $this->getBodyNode()->setAttribute('class', 'puma');
        $this->assertCount(1, $this->getBodyNode()->getClassList());
        $this->getBodyNode()->setAttribute('class', null);
        $this->assertCount(0, $this->getBodyNode()->getClassList());
        $this->getBodyNode()->setAttribute('class', 'model-22_g and coperate');
        $this->assertCount(3, $this->getBodyNode()->getClassList());
    }

    public function testElementParser(): void
    {
        $collection = $this->getBodyNode()->getElementsByClassName('case-2');

        $this->assertCount(2, $collection);

        $collection = $this->getBodyNode()->getElementsByClassName('case-2 case-1');

        $this->assertCount(1, $collection);

        $collection = $this->getBodyNode()->getElementsByTagName('img');

        $this->assertCount(1, $collection);

        $collection = $this->getBodyNode()->getElementsByTagName('*');

        $this->assertCount(8, $collection);

        $collection = $this->getBodyNode()->querySelectorAll('*.case-1');

        $this->assertCount(3, $collection);

        $this->assertTrue($this->getButtonNode()->matches('.btn'));

        $nodeBr = $this->getDivNode()->querySelector('br');

        $this->assertSame($this->getBrNode(), $nodeBr);
    }

    public function testSetInnerHTML(): void
    {
        $this->getANode()->setInnerHtml(HtmlLoaderTest::BOOTSTRAP_MODAL_STR);

        $this->assertCount(1, $this->getANode()->getChildNodes());

        /**
         * @var ElementInterface $modalElement
         */
        $modalElement = $this->getANode()->getFirstChild();

        $this->assertSame('modal', $modalElement->getAttribute('class'));
    }

    public function testElementVisibility(): void
    {
        $this->getFormNode()->setVisible(false);
        $this->assertNotNull($this->getDivNode()->querySelector('form'));
        $this->assertStringNotContainsString('<form action', $this->getDivNode()->render());
    }

    public function testClearChildNodes(): void
    {
        $this->assertSame($this->getButtonNode()->getParentElement(), $this->getFormNode());
        $this->getFormNode()->clearChildNodes();
        $this->assertCount(0, $this->getFormNode()->getChildNodes());
        $this->assertNull($this->getButtonNode()->getParentNode());
        $this->assertNull($this->getButtonNode()->getParentElement());
    }

    public function testCloneNode(): void
    {
        /**
         * @var ElementInterface
         */
        $divClone = $this->getDivNode()->cloneNode();

        $this->assertSame($divClone->getAttribute('class'), 'position-relative case-1');
        $this->assertSame($divClone->getAttribute('data-theme'), 'dark');
        $this->assertTrue($divClone->getChildren()->isEmpty());

        /**
         * @var ElementInterface
         */
        $divDeepClone = $this->getDivNode()->cloneNode(true);

        $this->assertFalse($divDeepClone->getChildren()->isEmpty());
        $this->assertNotSame($this->getDivNode(), $divDeepClone);
        $this->assertSame($this->getDivNode()->render(0), $divDeepClone->render(0));
        $this->assertSame($this->getDivNode()->getChildren()->count(), $divDeepClone->getChildren()->count());

        $textClone = $this->getTextNode()->cloneNode();

        $this->assertNotSame($this->getTextNode(), $textClone);
        $this->assertSame($this->getTextNode()->render(), $textClone->render());

        $bodyClone = $this->getBodyNode()->cloneNode(true);

        $this->assertNotSame($this->getBodyNode(), $bodyClone);
        $this->assertSame($this->getBodyNode()->render(), $bodyClone->render());
    }

    public function testElementMethods(): void
    {
        $this->assertSame($this->getBodyNode()->getElementById('factor'), $this->getImgNode());

        $parents = $this->getTextNode()->getParentElements();

        $this->assertCount(4, $parents);
        $this->assertTrue($parents->exists($this->getDivNode()));
        $this->assertSame($this->getFormNode(), $parents->get(1));

        $this->assertSame($this->getDivNode()->getNodeTypeEnum()->getLabel(), 'ELEMENT');
    }

    public function testChildAssignmentToNonElement(): void
    {
        $this->expectException(DOMException::class);
        $this->getTextNode()->appendChild($this->getInputNode());
    }

    public function testAncestorAsChildNode(): void
    {
        $this->expectException(DOMException::class);
        $this->getFormNode()->appendChild($this->getDivNode());
    }
}
