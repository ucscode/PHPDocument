<?php

namespace Ucscode\UssElement\Support\Internal;

use Ucscode\UssElement\Collection\NodeList;
use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Enums\NodeTypeEnum;

class NodeReadonly
{
    protected ?NodeInterface $parentNode = null;
    protected ?ElementInterface $parentElement = null;

    public function __construct(protected NodeList $nodeList, protected NodeTypeEnum $nodeType)
    {
        
    }

    public function getNodeType(): int
    {
        return $this->nodeType->value;
    }

    public function getChildNodes(): NodeList
    {
        return $this->nodeList;
    }

    public function getFirstChild(): ?NodeInterface
    {
        return $this->nodeList->first();
    }

    public function getLastChild(): ?NodeInterface
    {
        return $this->nodeList->last();
    }

    public function getNextSibling(NodeInterface $node): ?NodeInterface
    {
        return $this->getSibling($node, 1);
    }

    public function getPreviousSibling(NodeInterface $node): ?NodeInterface
    {
        return $this->getSibling($node, -1);
    }

    public function getParentNode(): ?NodeInterface
    {
        return $this->parentNode;
    }

    public function getParentElement(): ?ElementInterface
    {
        return $this->parentElement;
    }

    public function invokeGetter__(string $name, NodeInterface $node): mixed
    {
        $method = sprintf('get%s', ucfirst($name));
                
        if (!method_exists($this, $method)) {
            throw new \ErrorException("Undefined property: " . __CLASS__ . "::\$$name");
        }

        return $this->{$method}($node);
    }

    /**
     * @param NodeInterface $parentNode
     * @return void
     */
    public function setParentNode(?NodeInterface $parentNode): void
    {
        $this->parentNode = $parentNode;

        if ($parentNode instanceof ElementInterface || $parentNode === null) {
            $this->parentElement = $parentNode;
        }
    }

    /**
     * @param NodeInterface $node The node whose sibling should be gotten
     * @param int $index    The index of the sibling (next = 1, previous = -1)
     * @return NodeInterface|null
     */
    protected function getSibling(NodeInterface $node, int $index): ?NodeInterface
    {
        if ($this->parentNode) {
            $siblings = $this->parentNode->childNodes;

            if (false !== $key = $siblings->indexOf($node)) {
                return $siblings->get($key + $index);
            }
        }

        return null;
    }
}