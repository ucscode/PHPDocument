<?php

namespace Ucscode\UssElement\Support;

use Ucscode\UssElement\Enums\NodeNameEnum;
use Ucscode\UssElement\Collection\NodeList;
use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Parser\Translator\NodeJsonEncoder;
use Ucscode\UssElement\Support\Internal\ObjectReflector;

/**
 * @method void setParentNode(NodeInterface $parent) Sets the parent for the child element.
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
abstract class AbstractNode implements NodeInterface, \Stringable
{
    abstract public function getNodeType(): int;

    private int $nodeId;
    protected string $nodeName;
    protected bool $visible = true;
    protected ?NodeInterface $parentNode = null;
    protected ?ElementInterface $parentElement = null;
    protected NodeList $childNodes;


    public function __construct(string|NodeNameEnum $nodeName)
    {
        if ($nodeName instanceof NodeNameEnum) {
            $nodeName = $nodeName->value;
        }

        $this->nodeName = strtoupper($nodeName);
        $this->childNodes = new NodeList();
        $this->nodeId = NodeSingleton::getInstance()->getNextId();
    }

    public function __toString(): string
    {
        return $this->render(null);
    }

    final public function getNodeId(): int
    {
        return $this->nodeId;
    }

    final public function getNodeName(): string
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
        return $this->childNodes;
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
        return $this->childNodes->first() === $node;
    }

    public function getLastChild(): ?NodeInterface
    {
        return $this->childNodes->last();
    }

    public function isLastChild(NodeInterface $node): bool
    {
        return $this->childNodes->last() === $node;
    }

    /**
     * @param static $node
     * @see Ucscode\UssElement\Collection\NodeList::prepend()
     */
    public function prependChild(NodeInterface $node): static
    {
        (new ObjectReflector($this->childNodes))->invokeMethod('prepend', $node);

        $node->setParentNode($this);

        return $this;
    }

    /**
     * @param static $node
     * @see Ucscode\UssElement\Collection\NodeList::append()
     */
    public function appendChild(NodeInterface $node): static
    {
        (new ObjectReflector($this->childNodes))->invokeMethod('append', $node);

        $node->setParentNode($this);

        return $this;
    }

    /**
     * @param static $node
     * @see Ucscode\UssElement\Collection\NodeList::insertAt()
     */
    public function insertAdjacentNode(int $offset, NodeInterface $node): static
    {
        (new ObjectReflector($this->childNodes))->invokeMethod('insertAt', $offset, $node);

        $node->setParentNode($this);

        return $this;
    }

    /**
     * @param static $node
     * @see Ucscode\UssElement\Collection\NodeList::remove()
     */
    public function removeChild(NodeInterface $node): static
    {
        if ($this->hasChild($node)) {
            (new ObjectReflector($this->childNodes))->invokeMethod('remove', $node);

            $node->setParentNode(null);
        }

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
            // detach the new Node from its previous parent
            $newNode->getParentElement()?->removeChild($newNode);
            $this->insertAdjacentNode($this->childNodes->indexOf($referenceNode), $newNode);
        }

        return $this;
    }

    /**
     * @param static $newNode
     */
    public function insertAfter(NodeInterface $newNode, NodeInterface $referenceNode): static
    {
        if ($this->hasChild($referenceNode)) {
            // detach the new Node from its previous parent
            $newNode->getParentNode()?->removeChild($newNode);
            $key = $this->childNodes->indexOf($referenceNode);
            $this->insertAdjacentNode($key + 1, $newNode);
        }

        return $this;
    }

    public function replaceChild(NodeInterface $newNode, NodeInterface $oldNode): static
    {
        if ($this->hasChild($oldNode)) {
            $this->insertBefore($newNode, $oldNode);
            $this->removeChild($oldNode);
        }

        return $this;
    }

    public function sortChildNodes(callable $func): static
    {
        $this->childNodes->sort($func);

        return $this;
    }

    public function clearChildNodes(): static
    {
        /**
         * @var static $node
         */
        foreach ($this->getChildNodes()->toArray() as $node) {
            $this->removeChild($node);
        }

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
        $this->parentNode?->insertAdjacentNode($index, $this);

        return $this;
    }

    public function toJson(bool $prettyPrint = false): string
    {
        return (new NodeJsonEncoder($this))->encode($prettyPrint);
    }

    /**
     * @param NodeInterface $parentNode
     * @return void
     */
    protected function setParentNode(?NodeInterface $parentNode): void
    {
        $this->parentNode = $parentNode;

        if ($parentNode instanceof ElementInterface || $parentNode === null) {
            $this->parentElement = $parentNode;
        }
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

    /**
     * Helper method to generate indented values
     *
     * @param string|null $value The value to render
     * @param integer $tab The number of indentations
     * @param boolean $newline Whether to add new line after the content
     * @return string The indented value
     */
    protected function indent(?string $value, int $tab, bool $newline = true): string
    {
        return sprintf('%s%s%s', str_repeat("\t", $tab), $value ?? '', $newline ? "\n" : '');
    }
}
