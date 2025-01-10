<?php

namespace Ucscode\UssElement\Support;

use Ucscode\UssElement\Enums\NodeNameEnum;
use Ucscode\UssElement\Collection\NodeList;
use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Parser\Translator\NodeJsonEncoder;
use Ucscode\UssElement\Support\Internal\NodeReadonly;
use Ucscode\UssElement\Support\Internal\ObjectReflector;

/**
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
abstract class AbstractNode implements NodeInterface, \Stringable
{
    public readonly string $nodeName;
    public readonly int $nodeType;
    public readonly int $nodeId;
    protected bool $visible = true;
    protected NodeReadonly $readonly;

    public function __construct(string|NodeNameEnum $nodeName)
    {
        $this->nodeName = strtoupper($nodeName instanceof NodeNameEnum ? $nodeName->value : $nodeName);
        $this->nodeId = NodeSingleton::getInstance()->getNextId();
        $this->readonly = new NodeReadonly(new NodeList());
    }

    public function __toString(): string
    {
        return $this->render(null);
    }

    public function __get(string $name): mixed
    {
        $method = sprintf('get%s', ucfirst($name));

        if (!method_exists($this->readonly, $method)) {
            throw new \ErrorException("Undefined property: " . __CLASS__ . "::\$$name");
        }

        return $this->readonly->{$method}($this);
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

    public function isFirstChild(NodeInterface $node): bool
    {
        return $this->childNodes->first() === $node;
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

        $this->setParentNode($node, $this);

        return $this;
    }

    /**
     * @param static $node
     * @see Ucscode\UssElement\Collection\NodeList::append()
     */
    public function appendChild(NodeInterface $node): static
    {
        (new ObjectReflector($this->childNodes))->invokeMethod('append', $node);

        $this->setParentNode($node, $this);

        return $this;
    }

    /**
     * @param static $node
     * @see Ucscode\UssElement\Collection\NodeList::insertAt()
     */
    public function insertAdjacentNode(int $offset, NodeInterface $node): static
    {
        (new ObjectReflector($this->childNodes))->invokeMethod('insertAt', $offset, $node);

        $this->setParentNode($node, $this);

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

            $this->setParentNode($node, null);
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
            $newNode->getParentElement?->removeChild($newNode);
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
            $newNode->getParentNode?->removeChild($newNode);
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
        (new ObjectReflector($this->childNodes))->invokeMethod('sort', $func);

        return $this;
    }

    public function clearChildNodes(): static
    {
        /**
         * @var static $node
         */
        foreach ($this->childNodes->toArray() as $node) {
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
        if ($siblingNode->getParentNode === $this->parentNode) {
            $this->parentNode->insertBefore($this, $siblingNode);
        }

        return $this;
    }

    public function moveAfter(NodeInterface $siblingNode): static
    {
        if ($siblingNode->getParentNode === $this->parentNode) {
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

    /**
     * Undocumented function
     *
     * @param NodeInterface $node   The child node
     * @param NodeInterface|null $parentNode    The new parent node
     * @return void
     */
    private function setParentNode(NodeInterface $node, ?NodeInterface $parentNode): void
    {
        /**
         * Access protected readonly property of Node
         * @var NodeReadonly $readonly
         */ 
        $readonly = (new ObjectReflector($node))->getProperty('readonly');
        $readonly->setParentNode($parentNode);
    }
}
