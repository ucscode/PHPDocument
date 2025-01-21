<?php

namespace Ucscode\UssElement\Test\Traits;

use Ucscode\UssElement\Collection\NodeList;
use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Enums\NodeNameEnum;
use Ucscode\UssElement\Node\ElementNode;
use Ucscode\UssElement\Node\TextNode;
use Ucscode\UssElement\Parser\Engine\Transformer;

trait NodeHelperTrait
{
    protected NodeList $nodeList;
    protected Transformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new Transformer();

        $this->nodeList = new NodeList([
            new ElementNode(NodeNameEnum::NODE_BODY, [
                'class' => 'body',
                'id' => 'body',
            ]),
            new ElementNode(NodeNameEnum::NODE_DIV, [
                'class' => 'position-relative case-1',
                'data-theme' =>  'dark',
            ]),
            new ElementNode(NodeNameEnum::NODE_H1),
            new ElementNode(NodeNameEnum::NODE_FORM, [
                'action' => '',
                'name' => 'form'
            ]),
            new ElementNode(NodeNameEnum::NODE_INPUT, [
                'name' => 'username',
                'value' => '224',
                'type' => 'text',
                'class' => 'case-1 case-2',
                'id' => 'factor'
            ]),
            new ElementNode(NodeNameEnum::NODE_A, [
                'href' => 'https://example.com',
                'error' => 3,
            ]),
            new ElementNode(NodeNameEnum::NODE_BR),
            new ElementNode(NodeNameEnum::NODE_BUTTON, [
                'class' => 'btn btn-primary case-2',
                'type' => 'submit',
                'data-value' => '["data1", "data2"]',
            ]),
            new ElementNode(NodeNameEnum::NODE_IMG, [
                'src' => 'https://dummyimage.com/300x500/fff',
                'class' => 'img-fluid case-1',
                'id' => 'factor',
            ]),
            new TextNode('This is a text'),
        ]);

        $this->randomizeNodesHierarchy();
    }

    /**
     * @return ElementNode
     */
    protected function getBodyNode(): NodeInterface
    {
        return $this->nodeList->get(0);
    }

    /**
     * @return ElementNode
     */
    protected function getDivNode(): NodeInterface
    {
        return $this->nodeList->get(1);
    }

    /**
     * @return ElementNode
     */
    protected function getH1Node(): NodeInterface
    {
        return $this->nodeList->get(2);
    }

    /**
     * @return ElementNode
     */
    protected function getFormNode(): NodeInterface
    {
        return $this->nodeList->get(3);
    }

    /**
     * @return ElementNode
     */
    protected function getInputNode(): NodeInterface
    {
        return $this->nodeList->get(4);
    }

    /**
     * @return ElementNode
     */
    protected function getANode(): NodeInterface
    {
        return $this->nodeList->get(5);
    }

    /**
     * @return ElementNode
     */
    protected function getBrNode(): NodeInterface
    {
        return $this->nodeList->get(6);
    }

    /**
     * @return ElementNode
     */
    protected function getButtonNode(): NodeInterface
    {
        return $this->nodeList->get(7);
    }

    /**
     * @return ElementNode
     */
    protected function getImgNode(): NodeInterface
    {
        return $this->nodeList->get(8);
    }

    /**
     * @return ElementNode
     */
    protected function getTextNode(): NodeInterface
    {
        return $this->nodeList->get(9);
    }

    protected function randomizeNodesHierarchy(): void
    {
        // body > div
        $this->getBodyNode()->appendChild($this->getDivNode());

        // body > div > h1
        $this->getDivNode()->appendChild($this->getH1Node());
        // body > div > a
        $this->getDivNode()->appendChild($this->getANode());
        // body > div > form
        $this->getDivNode()->appendChild($this->getFormNode());

        // body > div > a > img
        $this->getANode()->appendChild($this->getImgNode());

        // body > div > form > input
        $this->getFormNode()->appendChild($this->getInputNode());
        // body > div > form > br
        $this->getFormNode()->appendChild($this->getBrNode());
        // body > div > form > button
        $this->getFormNode()->appendChild($this->getButtonNode());

        // body > div > form > button > text
        $this->getButtonNode()->appendChild($this->getTextNode());

        // Visualization
        /*
            <body>
                <div>
                    <h1></h1>
                    <a>
                        <img>
                    </a>
                    <form>
                        <input/>
                        <br/>
                        <button>
                            #text
                        </button>
                    </form>
                </div>
            </body>
        */
    }

    protected function encodeRawSelector(string $rawSelector): string
    {
        return $this->transformer->encodeAttributes(
            $this->transformer->encodeQuotedStrings($rawSelector)
        );
    }
}
