<?php

namespace Ucscode\UssElement\Test\Node;

use PHPUnit\Framework\TestCase;
use Ucscode\UssElement\Enums\NodeNameEnum;
use Ucscode\UssElement\Node\ElementNode;
use Ucscode\UssElement\Test\Parser\Translator\HtmlLoaderTest;
use Ucscode\UssElement\Test\Traits\NodeHelperTrait;

class ElementNodeTest extends TestCase
{
    use NodeHelperTrait;

    public function testInitialValues(): void
    {
        $this->assertSame($this->getNodeBody()->nodeName, NodeNameEnum::NODE_BODY->value);
        $this->assertTrue($this->getNodeImg()->isVoid());
        $this->assertFalse($this->getNodeButton()->isVoid());
        $this->assertCount(1, $this->getNodeBody()->children);
        $this->assertNotNull($this->getNodeDiv()->parentNode);
        $this->assertNotNull($this->getNodeDiv()->parentElement);
        $this->assertSame($this->getNodeForm(), $this->getNodeInput()->parentElement);
        $this->assertNull($this->getNodeBody()->parentNode);
        $this->assertTrue($this->getNodeButton()->hasChild($this->getNodeText()));
        $this->assertSame($this->getNodeBr()->previousSibling, $this->getNodeInput());
        $this->assertSame($this->getNodeBr()->nextSibling, $this->getNodeButton());
        $this->assertNull($this->getNodeButton()->nextSibling);
        $this->assertNull($this->getNodeH1()->previousSibling);
        $this->assertSame($this->getNodeForm()->firstChild, $this->getNodeInput());
        $this->assertSame($this->getNodeForm()->lastChild, $this->getNodeButton());
        $this->assertCount(3, $this->getNodeForm()->childNodes);
        $this->assertCount(1, $this->getNodeButton()->childNodes);
        $this->assertCount(0, $this->getNodeButton()->children);
    }

    public function testArrangementLogic(): void
    {
        $this->getNodeBody()->prependChild($this->getNodeA());

        $this->assertCount(2, $this->getNodeBody()->childNodes);
        $this->assertCount(2, $this->getNodeDiv()->childNodes);
        $this->assertSame($this->getNodeBody()->firstChild, $this->getNodeA());
        $this->assertSame($this->getNodeBody()->lastChild, $this->getNodeDiv());
        $this->assertSame($this->getNodeBody()->childNodes->get(1), $this->getNodeDiv());
        $this->assertSame($this->getNodeA()->firstChild, $this->getNodeImg());
        $this->assertSame($this->getNodeA()->lastChild, $this->getNodeImg());
        $this->assertCount(3, $this->getNodeForm()->childNodes);
        $this->assertSame($this->getNodeA()->parentElement, $this->getNodeBody());
        $this->assertSame($this->getNodeForm()->firstElementChild, $this->getNodeInput());
        $this->assertSame($this->getNodeForm()->lastElementChild, $this->getNodeButton());
        $this->assertSame($this->getNodeA()->nextElementSibling, $this->getNodeDiv());
        $this->assertSame($this->getNodeA()->previousElementSibling, null);
        $this->assertNull($this->getNodeButton()->firstElementChild);
        $this->assertNull($this->getNodeButton()->lastElementChild);
        $this->assertNull($this->getNodeBr()->lastElementChild);
        $this->assertNull($this->getNodeBody()->nextElementSibling);

        $this->getNodeForm()->insertAdjacentNode(1, $this->getNodeImg());
        $this->assertCount(0, $this->getNodeA()->childNodes);
        $this->assertCount(4, $this->getNodeForm()->childNodes);

        $this->getNodeForm()->insertBefore($this->getNodeButton(), $this->getNodeInput());

        $this->assertSame($this->getNodeForm()->firstChild, $this->getNodeButton());
        $this->assertSame($this->getNodeForm()->lastChild, $this->getNodeBr());
        $this->assertSame($this->getNodeForm()->childNodes->get(2), $this->getNodeImg());

        $this->getNodeForm()->insertAfter($this->getNodeH1(), $this->getNodeButton());

        $this->assertSame($this->getNodeForm()->childNodes->get(1), $this->getNodeH1());
        $this->assertSame($this->getNodeButton()->nextSibling, $this->getNodeH1());
        $this->assertSame($this->getNodeH1()->previousSibling, $this->getNodeButton());
        $this->assertCount(5, $this->getNodeForm()->childNodes);
        $this->assertCount(1, $this->getNodeDiv()->childNodes);
        $this->assertSame($this->getNodeForm(), $this->getNodeDiv()->firstChild);

        $this->assertTrue($this->getNodeForm()->hasChild($this->getNodeH1()));

        $selectNode = new ElementNode(NodeNameEnum::NODE_SELECT);

        $this->getNodeForm()->replaceChild($selectNode, $this->getNodeH1());

        $this->assertFalse($this->getNodeForm()->hasChild($this->getNodeH1()));
        $this->assertTrue($this->getNodeForm()->hasChild($selectNode));
        $this->assertCount(5, $this->getNodeForm()->childNodes);
        $this->assertSame($selectNode->previousSibling, $this->getNodeButton());

        $selectNode->moveToFirst();

        $this->assertSame($this->getNodeForm()->firstChild, $selectNode);

        $selectNode->moveToLast();

        $this->assertSame($this->getNodeForm()->lastChild, $selectNode);

        $selectNode->moveToIndex(3);

        $this->assertSame($this->getNodeForm()->childNodes->get(3), $selectNode);

        $selectNode->moveBefore($this->getNodeImg());

        $this->assertSame($selectNode->nextSibling, $this->getNodeImg());

        $selectNode->moveAfter($this->getNodeImg());

        $this->assertSame($selectNode->previousSibling, $this->getNodeImg());

        $this->getNodeForm()->sortChildNodes(function ($a, $b) {
            return strcmp($a->nodeName, $b->nodeName);
        });

        $this->assertSame($this->getNodeForm()->firstChild, $this->getNodeBr());
        $this->assertSame($this->getNodeForm()->getChild(1), $this->getNodeButton());
        $this->assertSame($this->getNodeForm()->getChild(2), $this->getNodeImg());
        $this->assertSame($this->getNodeForm()->getChild(3), $this->getNodeInput());
        $this->assertSame($this->getNodeForm()->lastChild, $selectNode);

        foreach ($this->getNodeForm()->children as $kid) {

        };
    }

    public function testElementAttributes(): void
    {
        $this->assertTrue($this->getNodeBody()->hasAttribute('id'));
        $this->getNodeBody()->classList->add('super legis supreme');
        $this->assertStringContainsString('super', $this->getNodeBody()->getAttribute('class'));
        $this->assertCount(4, $this->getNodeBody()->classList);
        $this->getNodeBody()->setAttribute('class', 'puma');
        $this->assertCount(1, $this->getNodeBody()->classList);
        $this->getNodeBody()->setAttribute('class', null);
        $this->assertCount(0, $this->getNodeBody()->classList);
        $this->getNodeBody()->setAttribute('class', 'model-22_g and coperate');
        $this->assertCount(3, $this->getNodeBody()->classList);
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

        $this->assertCount(1, $this->getNodeA()->childNodes);

        /**
         * @var ElementInterface $modalElement
         */
        $modalElement = $this->getNodeA()->firstChild;

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
        $this->assertSame($this->getNodeButton()->parentElement, $this->getNodeForm());
        $this->getNodeForm()->clearChildNodes();
        $this->assertCount(0, $this->getNodeForm()->childNodes);
        $this->assertNull($this->getNodeButton()->parentNode);
        $this->assertNull($this->getNodeButton()->parentElement);
    }

    public function testCloneNode(): void
    {
        $divClone = $this->getNodeDiv()->cloneNode();

        $this->assertSame($divClone->getAttribute('class'), 'position-relative case-1');
        $this->assertSame($divClone->getAttribute('data-theme'), 'dark');
        $this->assertTrue($divClone->children->isEmpty());

        $divDeepClone = $this->getNodeDiv()->cloneNode(true);

        $this->assertSame($this->getNodeDiv()->render(0), $divDeepClone->render(0));
    }
}
