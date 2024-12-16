<?php

namespace Ucscode\PHPDocument\Test\Traits;

use Ucscode\PHPDocument\Collection\NodeList;
use Ucscode\PHPDocument\Contracts\NodeInterface;
use Ucscode\PHPDocument\Contracts\ElementInterface;
use Ucscode\PHPDocument\Enums\NodeEnum;
use Ucscode\PHPDocument\Node\ElementNode;
use Ucscode\PHPDocument\Node\TextNode;

trait NodeHelperTrait
{
    /**
     * @return ElementInterface
     */
    protected NodeList $nodeList;

    /**
     * @return ElementInterface
     */
    protected function setUp(): void
    {
        $this->nodeList = new NodeList([
            new ElementNode(NodeEnum::NODE_BODY),
            new ElementNode(NodeEnum::NODE_DIV),
            new ElementNode(NodeEnum::NODE_H1),
            new ElementNode(NodeEnum::NODE_FORM),
            new ElementNode(NodeEnum::NODE_INPUT),
            new ElementNode(NodeEnum::NODE_A),
            new ElementNode(NodeEnum::NODE_BR),
            new ElementNode(NodeEnum::NODE_BUTTON),
            new ElementNode(NodeEnum::NODE_IMG),
            new TextNode('This is a text'),
        ]);

        $this->randomizeNodesHierarchy();
    }

    /**
     * @return ElementInterface
     */
    protected function getNodeBody(): NodeInterface
    {
        return $this->nodeList->get(0);
    }

    /**
     * @return ElementInterface
     */
    protected function getNodeDiv(): NodeInterface
    {
        return $this->nodeList->get(1);
    }

    /**
     * @return ElementInterface
     */
    protected function getNodeH1(): NodeInterface
    {
        return $this->nodeList->get(2);
    }

    /**
     * @return ElementInterface
     */
    protected function getNodeForm(): NodeInterface
    {
        return $this->nodeList->get(3);
    }

    /**
     * @return ElementInterface
     */
    protected function getNodeInput(): NodeInterface
    {
        return $this->nodeList->get(4);
    }

    /**
     * @return ElementInterface
     */
    protected function getNodeA(): NodeInterface
    {
        return $this->nodeList->get(5);
    }

    /**
     * @return ElementInterface
     */
    protected function getNodeBr(): NodeInterface
    {
        return $this->nodeList->get(6);
    }

    /**
     * @return ElementInterface
     */
    protected function getNodeButton(): NodeInterface
    {
        return $this->nodeList->get(7);
    }

    /**
     * @return ElementInterface
     */
    protected function getNodeImg(): NodeInterface
    {
        return $this->nodeList->get(8);
    }

    /**
     * @return ElementInterface
     */
    protected function getNodeText(): NodeInterface
    {
        return $this->nodeList->get(9);
    }

    protected function randomizeNodesHierarchy(): void
    {
        // body > div
        $this->getNodeBody()
            ->appendChild($this->getNodeDiv())
        ;

        // body >
        $this->getNodeDiv()
            // div > h1
            ->appendChild($this->getNodeH1())
            // div > a
            ->appendChild($this->getNodeA())
            // div > form
            ->appendChild($this->getNodeForm())
        ;

        // body > div >
        $this->getNodeA()
            // a > img
            ->appendChild($this->getNodeImg())
        ;

        // body > div >
        $this->getNodeForm()
            // form > input
            ->appendChild($this->getNodeInput())
            // form > br
            ->appendChild($this->getNodeBr())
            // form > button
            ->appendChild($this->getNodeButton())
        ;

        // body > div > form >
        $this->getNodeButton()
            // button > text
            ->appendChild($this->getNodeText())
        ;

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
}
