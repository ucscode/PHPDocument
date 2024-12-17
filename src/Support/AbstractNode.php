<?php

namespace Ucscode\PHPDocument\Support;

use Ucscode\PHPDocument\Collection\MutableNodeList;
use Ucscode\PHPDocument\Enums\NodeEnum;
use Ucscode\PHPDocument\Collection\NodeList;
use Ucscode\PHPDocument\Contracts\ElementInterface;
use Ucscode\PHPDocument\Contracts\NodeInterface;

/**
 * @method void setParentNode(NodeInterface $parent) Sets the parent for the child element.
 */
abstract class AbstractNode implements NodeInterface, \Stringable
{
    abstract public function getNodeType(): int;

    protected string $nodeName;
    protected bool $visible = true;
    protected ?NodeInterface $parentNode = null;
    protected ?ElementInterface $parentElement = null;

    /**
     * @var MutableNodeList<int, NodeInterface>
     */
    protected MutableNodeList $childNodes;

    public function __construct(string|NodeEnum $nodeName)
    {
        if ($nodeName instanceof NodeEnum) {
            $nodeName = $nodeName->value;
        }

        $this->nodeName = strtoupper($nodeName);
        $this->childNodes = new MutableNodeList();
        $this->nodePresets();
    }

    public function __toString(): string
    {
        return $this->render();
    }

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
        return new NodeList($this->childNodes->toArray());
    }

    public function getNextSibling(): ?NodeInterface
    {
        return $this->getSibling(1);
    }

    public function getPreviousSibling(): ?NodeInterface
    {
        return $this->getSibling(-1);
    }

    public function getFirstChild(): ?NodeInterface
    {
        return $this->childNodes->first();
    }

    public function isFirstChild(NodeInterface $node): bool
    {
        return $this->childNodes->isFirst($node);
    }

    public function getLastChild(): ?NodeInterface
    {
        return $this->childNodes->last();
    }

    public function isLastChild(NodeInterface $node): bool
    {
        return $this->childNodes->isLast($node);
    }

    /**
     * @param static $node
     */
    public function prependChild(NodeInterface $node): static
    {
        $this->childNodes->prepend($node);
        $node->setParentNode($this);

        return $this;
    }

    /**
     * @param static $node
     */
    public function appendChild(NodeInterface $node): static
    {
        $this->childNodes->append($node);
        $node->setParentNode($this);

        return $this;
    }

    /**
     * @param static $node
     */
    public function insertChild(int $offset, NodeInterface $node): static
    {
        $this->childNodes->insertAt($offset, $node);
        $node->setParentNode($this);

        return $this;
    }

    /**
     * @param static $node
     */
    public function removeChild(NodeInterface $node): static
    {
        $this->childNodes->remove($node);
        $node->setParentNode(null);

        return $this;
    }

    public function hasChild(NodeInterface $node): bool
    {
        return $this->childNodes->exists($node);
    }

    public function getChild(int $offset): ?NodeInterface
    {
        return $this->childNodes->get($offset);
    }

    /**
     * @param static $newNode
     */
    public function insertBefore(NodeInterface $newNode, NodeInterface $referenceNode): static
    {
        if ($this->hasChild($referenceNode)) {
            $this->removeChild($newNode); // reset the node keys
            $this->insertChild($this->childNodes->indexOf($referenceNode), $newNode);
        }

        return $this;
    }

    /**
     * @param static $newNode
     */
    public function insertAfter(NodeInterface $newNode, NodeInterface $referenceNode): static
    {
        if ($this->hasChild($referenceNode)) {
            $this->removeChild($newNode); // reset the node keys
            $key = $this->childNodes->indexOf($referenceNode);
            $this->insertChild($key + 1, $newNode);
        }

        return $this;
    }

    public function replaceChild(NodeInterface $newNode, NodeInterface $oldNode): static
    {
        $this->insertBefore($newNode, $oldNode);
        $this->removeChild($oldNode);

        return $this;
    }

    public function sortChildNodes(callable $func): static
    {
        $this->childNodes->sort($func);

        return $this;
    }

    public function cloneNode(bool $deep = false): NodeInterface
    {
        $clone = new static($this->nodeName);
        $clone->visible = true;

        if ($deep) {
            $childClones = array_map(
                fn (NodeInterface $node) => $node->cloneNode(true),
                $this->childNodes->toArray()
            );

            $clone->childNodes = new NodeList($childClones);
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

    public function moveToFirst(): static
    {
        $this->parentNode?->prependChild($this);

        return $this;
    }

    public function moveToLast(): static
    {
        $this->parentNode?->appendChild($this);

        return $this;
    }

    public function moveToIndex(int $index): static
    {
        $this->parentNode?->insertChild($index, $this);

        return $this;
    }

    /**
     * @param NodeInterface $parentNode
     * @return void
     */
    protected function setParentNode(?NodeInterface $parentNode): void
    {
        $this->parentNode = $parentNode;

        if ($parentNode instanceof ElementInterface) {
            $this->parentElement = $parentNode;
        }
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
