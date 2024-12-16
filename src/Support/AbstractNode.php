<?php

namespace Ucscode\PHPDocument\Support;

use Ucscode\Element\Enums\NodeNameEnum;
use Ucscode\PHPDocument\Collection\NodeList;
use Ucscode\PHPDocument\Contracts\ElementInterface;
use Ucscode\PHPDocument\Contracts\NodeInterface;

abstract class AbstractNode implements NodeInterface, \Stringable
{
    abstract public function getNodeType(): int;

    protected string $nodeName;
    protected ?NodeInterface $parentNode = null;
    protected ?ElementInterface $parentElement = null;
    /**
     * @var NodeList<int, NodeInterface>
     */
    protected NodeList $childNodes;
    protected bool $visible = true;

    public function __construct(string|NodeNameEnum $nodeName)
    {
        if ($nodeName instanceof NodeNameEnum) {
            $nodeName = $nodeName->value;
        }

        $this->nodeName = strtoupper($nodeName);
        $this->nodePresets();
    }

    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Return the name of the current node
     *
     * @return string
     */
    public function getNodeName(): string
    {
        return $this->nodeName;
    }

    public function getParentNode(): ?NodeInterface
    {
        return $this->parentNode;
    }

    public function getParentElement(): ?ElementInterface
    {
        return $this->parentElement;
    }

    public function setVisible(bool $visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function getChildNodes(): NodeList
    {
        return $this->childNodes->getReplica();
    }

    public function getNextSibling(): ?NodeInterface
    {
        return $this->getSibling(1);
    }

    public function getPreviousSibling(): ?NodeInterface
    {
        return $this->getSibling(-1);
    }

    public function prependChild(NodeInterface $node): static
    {
        $this->childNodes->prepend($node);

        return $this;
    }

    public function appendChild(NodeInterface $node): static
    {
        $this->childNodes->append($node);

        return $this;
    }

    public function getFirstChild(): ?NodeInterface
    {
        return $this->childNodes->first();
    }

    public function getLastChild(): ?NodeInterface
    {
        return $this->childNodes->last();
    }

    public function insertChild(int $offset, NodeInterface $node): static
    {
        $this->childNodes->insertAt($offset, $node);

        return $this;
    }

    public function insertBefore(NodeInterface $newNode, NodeInterface $referenceNode): static
    {
        if (false !== $key = $this->childNodes->indexOf($referenceNode)) {
            $this->childNodes->insertAt($key, $newNode);
        }

        return $this;
    }

    public function insertAfter(NodeInterface $newNode, NodeInterface $referenceNode): static
    {
        if (false !== $key = $this->childNodes->indexOf($referenceNode)) {
            $this->childNodes->insertAt($key + 1, $newNode);
        }

        return $this;
    }

    public function replaceChild(NodeInterface $newNode, NodeInterface $oldNode): static
    {
        $this->insertBefore($newNode, $oldNode);
        $this->removeChild($oldNode);

        return $this;
    }

    public function removeChild(NodeInterface $node): static
    {
        $this->childNodes->remove($node);

        return $this;
    }

    public function sortChildNodes(callable $func): static
    {
        $this->childNodes->sort($func);

        return $this;
    }

    public function cloneNode(bool $deep = false): NodeInterface
    {
        $clone = new self($this->nodeName);
        $clone->visible = true;

        if ($deep) {
            $clone->childNodes = $this->childNodes->map(function (NodeInterface $node) {
                return $node->cloneNode(true);
            });
        }

        return $clone;
    }

    public function moveBefore(NodeInterface $siblingNode): static
    {
        if ($siblingNode->getParentNode() === $this->parentNode) {
            $this->parentNode->insertBefore($this, $siblingNode);
        }

        return $this;
    }

    public function moveAfter(NodeInterface $siblingNode): static
    {
        if ($siblingNode->getParentNode() === $this->parentNode) {
            $this->parentNode->insertAfter($this, $siblingNode);
        }

        return $this;
    }

    /**
     * Do custom constructor configuration if you do not want to modify the default constructor
     *
     * @return void
     */
    protected function nodePresets(): void
    {
        // Your preset logics
    }

    /**
     * @param integer $index Unsigned
     * @return NodeInterface|null
     */
    private function getSibling(int $index): ?NodeInterface
    {
        if ($this->parentNode) {
            $parentNodelist = $this->parentNode->getChildNodes();

            if (false !== $key = $parentNodelist->indexOf($this)) {
                return $parentNodelist->get($key + $index);
            }
        }

        return null;
    }
}
