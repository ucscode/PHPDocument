<?php

namespace Ucscode\UssElement\Support\Internal;

use Ucscode\UssElement\Collection\NodeList;
use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Contracts\NodeInterface;

final class NodeReadonly
{
    private ?NodeInterface $parentNode = null;
    private ?ElementInterface $parentElement = null;

    public function __construct(protected NodeList $nodeList)
    {
        
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
        return $this->getSibling(1, $node);
    }

    public function getPreviousSibling(NodeInterface $node): ?NodeInterface
    {
        return $this->getSibling(-1, $node);
    }

    public function getParentNode(): NodeInterface
    {
        return $this->parentNode;
    }

    public function getParentElement(): ?ElementInterface
    {
        return $this->parentElement;
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
     * @param integer $index Unsigned
     * @param NodeInterface $self
     * @return NodeInterface|null
     */
    protected function getSibling(int $index, NodeInterface $self): ?NodeInterface
    {
        if ($this->parentNode) {
            $parentNodelist = $this->parentNode->childNodes;
            
            if (false !== $key = $parentNodelist->indexOf($self)) {
                return $parentNodelist->get($key + $index);
            }
        }

        return null;
    }

}