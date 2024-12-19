<?php

namespace Ucscode\PHPDocument\Test\Traits;

use Ucscode\PHPDocument\Collection\NodeList;
use Ucscode\PHPDocument\Contracts\NodeInterface;
use Ucscode\PHPDocument\Enums\NodeNameEnum;
use Ucscode\PHPDocument\Node\ElementNode;
use Ucscode\PHPDocument\Node\TextNode;
use Ucscode\PHPDocument\Parser\Engine\Transformer;

trait NodeHelperTrait
{
    /**
     * @return ElementNode
     */
    protected NodeList $nodeList;
    protected Transformer $transformer;

    /**
     * @return ElementNode
     */
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
    protected function getNodeBody(): NodeInterface
    {
        return $this->nodeList->get(0);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeDiv(): NodeInterface
    {
        return $this->nodeList->get(1);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeH1(): NodeInterface
    {
        return $this->nodeList->get(2);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeForm(): NodeInterface
    {
        return $this->nodeList->get(3);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeInput(): NodeInterface
    {
        return $this->nodeList->get(4);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeA(): NodeInterface
    {
        return $this->nodeList->get(5);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeBr(): NodeInterface
    {
        return $this->nodeList->get(6);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeButton(): NodeInterface
    {
        return $this->nodeList->get(7);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeImg(): NodeInterface
    {
        return $this->nodeList->get(8);
    }

    /**
     * @return ElementNode
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

    protected function encodeRawSelector(string $rawSelector): string
    {
        return $this->transformer->encodeAttributes(
            $this->transformer->encodeQuotedStrings($rawSelector)
        );
    }

    protected function dump(mixed $value): void
    {
        var_dump($value);
        echo "\n\n";
    }
}
