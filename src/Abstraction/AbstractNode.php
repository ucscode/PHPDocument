<?php

namespace Ucscode\UssElement\Abstraction;

use Ucscode\UssElement\Collection\ElementList;
use Ucscode\UssElement\Collection\NodeList;
use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Exception\DOMException;
use Ucscode\UssElement\Serializer\NodeJsonEncoder;
use Ucscode\UssElement\Support\Assertion;
use Ucscode\UssElement\Support\NodeState;
use Ucscode\UssElement\Support\ObjectReflector;

/**
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
abstract class AbstractNode implements NodeInterface, \Stringable
{
    protected string $nodeName;
    protected bool $visible = true;
    /**
     * @var NodeList<int, NodeInterface>
     */
    protected NodeList $childNodes;
    protected ?NodeInterface $parentNode = null;
    protected ?ElementInterface $parentElement = null;
    private int $nodeId;

    public function __construct()
    {
        $this->nodeId = NodeState::getInstance()->getNextId();
        $this->childNodes = new NodeList();
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

    final public function getNodeType(): int
    {
        return $this->getNodeTypeEnum()->value;
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

    /**
     * @return NodeList<int, NodeInterface>
     */
    public function getChildNodes(): NodeList
    {
        return $this->childNodes;
    }

    public function getNextSibling(): ?NodeInterface
    {
        return $this->getNodeSibling($this->parentNode?->getChildNodes(), 1);
    }
    
    public function getPreviousSibling(): ?NodeInterface
    {
        return $this->getNodeSibling($this->parentNode?->getChildNodes(), -1);
    }

    public function getFirstChild(): ?NodeInterface
    {
        return $this->childNodes->first();
    }

    public function getLastChild(): ?NodeInterface
    {
        return $this->childNodes->last();
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
     * @param NodeInterface $node
     * @see Ucscode\UssElement\Collection\NodeList::insertAt()
     */
    public function insertChildAtPosition(int $offset, NodeInterface $node): ?NodeInterface
    {
        (new Assertion())->assertCanAcceptChildNode($this, $node);
        
        /** @var bool $inserted */
        $inserted = (new ObjectReflector($this->childNodes))->invokeMethod('insertAt', $offset, $node);
        
        if ($inserted) {
            /**
             * set current node as parent element
             * @var self $node
             */
            $node->setParentNode($this); // -> consider using ObjectReflector

            return $node;
        }

        return null;
    }
    
    /**
     * @param NodeInterface $node
     * @throws DOMException if node is not an Element or Document
     * @throws DOMException if node (parameter) is same as the target node or an ancestor of the target node
     * @see Ucscode\UssElement\Collection\NodeList::prepend()
     */
    public function prependChild(NodeInterface $node): ?NodeInterface
    {
        (new Assertion())->assertCanAcceptChildNode($this, $node);
        
        /** @var bool $prepended */
        $prepended = (new ObjectReflector($this->childNodes))->invokeMethod('prepend', $node);

        if ($prepended) {
            /**
             * set parent node to the current element
             * @var self $node
             */
            $node->setParentNode($this); // -> consider using ObjectReflector

            return $node;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     * @throws DOMException if node is not an Element or Document
     * @throws DOMException if node (parameter) is same as the target node or an ancestor of the target node
     * @see Ucscode\UssElement\Collection\NodeList::append()
     */
    public function appendChild(NodeInterface $node): ?NodeInterface
    {
        (new Assertion())->assertCanAcceptChildNode($this, $node);

        /** @var bool $appended */
        $appended = (new ObjectReflector($this->childNodes))->invokeMethod('append', $node);

        if ($appended) {
            /**
             * set parent node to the current element
             * @var self $node
             */
            $node->setParentNode($this); // -> consider using ObjectReflector

            return $node;
        }

        return null;
    }

    /**
     * @param NodeInterface $node
     * @throws DOMException if the node is not a child of the target node
     * @see Ucscode\UssElement\Collection\NodeList::remove()
     */
    public function removeChild(NodeInterface $node): ?NodeInterface
    {
        (new Assertion())->assertChildExists($this, $node);

        /**  @var bool $removed */
        $removed = (new ObjectReflector($this->childNodes))->invokeMethod('remove', $node);

        if ($removed) {
            /** 
             * Set parent node to null; indicating it no longer has a parent
             * @var self $node 
             */
            $node->setParentNode(null); // -> consider using ObjectReflector

            return $node;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     * @throws DOMException If the child node is not an actual child of the parent element
     */
    public function insertBefore(NodeInterface $newNode, NodeInterface $childNode): ?NodeInterface
    {
        (new Assertion())->assertChildExists($this, $childNode);
        
        if ($this->hasChild($newNode)) {
            $this->removeChild($newNode);
        }

        return $this->insertChildAtPosition($this->childNodes->indexOf($childNode), $newNode);
    }

    /**
     * {@inheritDoc}
     * @throws DOMException If the child node is not an actual child of the parent element
     */
    public function insertAfter(NodeInterface $newNode, NodeInterface $childNode): ?NodeInterface
    {
        (new Assertion())->assertChildExists($this, $childNode);
        
        if ($this->hasChild($newNode)) {
            $this->removeChild($newNode);
        }

        return $this->insertChildAtPosition($this->childNodes->indexOf($childNode) + 1, $newNode);
    }

    /**
     * {@inheritDoc}
     * @throws DOMException if oldNode does not have a parent
     * @throws DOMException if oldNode is not an actual child of the parent element
     */
    public function replaceChild(NodeInterface $newNode, NodeInterface $oldNode): ?NodeInterface
    {
        (new Assertion())->assertChildExists($this, $oldNode);

        if ($this->insertBefore($newNode, $oldNode)) {
            return $this->removeChild($oldNode);
        }

        return null;
    }

    public function sortChildNodes(callable $func): static
    {
        $this->childNodes->sort($func);

        return $this;
    }

    public function clearChildNodes(): void
    {
        foreach ($this->childNodes->toArray() as $node) {
            $this->removeChild($node);
        }
    }

    public function cloneNode(bool $deep = false): static
    {
        $nodeReflection = new \ReflectionClass($this);

        /**
         * Create a clone without calling the __constructor
         *
         * @var static $clone
         */
        $clone = $nodeReflection->newInstanceWithoutConstructor();

        // iterate properties of the target node
        foreach ($nodeReflection->getProperties() as $property) {
            
            $property->setAccessible(true);
            $value = $property->getValue($this);
            // $name = $property->getName();

            // Handle shallow cloning
            if (!$deep) {
                if ($value instanceof NodeList) {
                    $value = new NodeList();
                }

                if ($value instanceof NodeInterface && $property->getType()->allowsNull()) {
                    $value = null;
                }
            }

            if ($deep) {
                if ($value instanceof NodeInterface) {
                    $value = $value->cloneNode(true); // Recursively clone child nodes
                }

                if (is_array($value)) {
                    $value = array_map(
                        fn ($item) => $item instanceof NodeInterface ? $item->cloneNode(true) : $item,
                        $value
                    );
                }
            }

            // Assign the cloned or original value to the clone
            $property->setValue($clone, $value);
        }

        return $clone;
    }

    public function hasSibling(NodeInterface $siblingNode): bool
    {
        return $this->parentNode?->hasChild($siblingNode);    
    }

    public function moveBeforeSibling(NodeInterface $siblingNode): ?NodeInterface
    {
        (new Assertion())->canChangeSiblingsPosition($this, $siblingNode);
        
        $this->parentNode->insertBefore($this, $siblingNode);

        return $siblingNode;
    }

    public function moveAfterSibling(NodeInterface $siblingNode): ?NodeInterface
    {
        (new Assertion())->canChangeSiblingsPosition($this, $siblingNode);

        $this->parentNode->insertAfter($this, $siblingNode);

        return $siblingNode;
    }

    public function moveToFirstSibling(): ?NodeInterface
    {
        $firstSibling = $this->parentNode->getFirstChild();

        $this->parentNode?->prependChild($this);

        return $firstSibling;
    }

    public function moveToLastSibling(): ?NodeInterface
    {
        $lastSibling = $this->parentNode->getLastChild();

        $this->parentNode?->appendChild($this);

        return $lastSibling;
    }

    public function moveToSiblingPosition(int $index): ?NodeInterface
    {
        $indexedSibling = $this->parentNode->getChild($index);

        $this->parentNode?->insertChildAtPosition($index, $this);

        return $indexedSibling;
    }

    public function toJson(bool $prettyPrint = false): string
    {
        return (new NodeJsonEncoder($this))->encode($prettyPrint);
    }

    /**
     * @return NodeList<int, NodeInterface>
     */
    public function getParentNodes(): NodeList
    {
        $node = $this;
        $parents = [];

        while ($node->getParentNode()) {
            $node = $node->getParentNode();
            $parents[] = $node;
        }

        return new NodeList($parents);
    }

    /**
     * @return ElementList<int, ElementInterface>
     */
    public function getParentElements(): ElementList
    {
        $parents = [];

        foreach ($this->getParentNodes() as $node) {
            if ($node instanceof ElementInterface) {
                $parents[] = $node;
            }
        }

        return new ElementList($parents);
    }

    /**
     * @param ?NodeInterface $parentNode
     * @return void
     */
    protected function setParentNode(?NodeInterface $parentNode): void
    {
        $this->parentNode = $parentNode;

        if ($parentNode === null || $parentNode instanceof ElementInterface) {
            $this->parentElement = $parentNode;
        }
    }

    /**
     * @param NodeList<int, NodeInterface> $siblings
     * @param integer $index Unsigned
     * @return NodeInterface|null
     */
    protected function getNodeSibling(?NodeList $siblings, int $index): ?NodeInterface
    {
        if ($siblings) {
            $currentNodeIndex = $siblings->indexOf($this);

            if ($currentNodeIndex !== false) {
                return $siblings->get($currentNodeIndex + $index);
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
