<?php

namespace Ucscode\UssElement\Collection;

use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Exception\InvalidNodeException;
use Ucscode\UssElement\Support\AbstractCollection;

/**
 * An instance of this class contains items that implement the NodeInterface
 *
 * @template T
 * @implements IteratorAggregate<int, NodeInterface>
 * @property NodeInterface[] $items
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class NodeList extends AbstractCollection
{
    /**
     * Get a node from the list
     *
     * @param integer $index
     * @return NodeInterface|null
     */
    public function get(int $index): ?NodeInterface
    {
        return $this->items[$index] ?? null;
    }

    /**
     * Get the first existing node or null if list is empty
     *
     * @return NodeInterface|null
     */
    public function first(): ?NodeInterface
    {
        return $this->isEmpty() ? null : $this->get(0);
    }

    /**
     * Get the last existing node or null if list is empty
     *
     * @return NodeInterface
     */
    public function last(): ?NodeInterface
    {
        return $this->isEmpty() ? null : $this->get($this->count() - 1);
    }

    /**
     * Get the index number of a specific node
     *
     * @param NodeInterface $node
     * @return integer|boolean
     */
    public function indexOf(NodeInterface $node): int|bool
    {
        return array_search($node, $this->items, true);
    }

    /**
     * Check if a node exists within the list
     *
     * @param NodeInterface $node
     * @return boolean
     */
    public function exists(NodeInterface $node): bool
    {
        return $this->indexOf($node) !== false;
    }

    /**
     * Replace every node in the item list
     *
     * @param array $items
     * @return static
     */
    protected function replace(array $items): static
    {
        foreach ($items as $item) {
            $this->validateItemType($item);
        }

        $this->items = $items;

        return $this;
    }

    /**
     * Insert the given node at a specific position within the list
     *
     * @param integer $index
     * @param NodeInterface $node
     * @return static
     */
    protected function insertAt(int $index, NodeInterface $node): static
    {
        if ($this->removeParentNode($node)) {
            array_splice($this->items, $index, 0, [$node]);
        }

        return $this;
    }

    /**
     * Add a node to the beginning of the list
     *
     * @param NodeInterface $node
     * @return static
     */
    protected function prepend(NodeInterface $node): static
    {
        if ($this->removeParentNode($node)) {
            array_unshift($this->items, $node);
        }

        return $this;
    }

    /**
     * Add a node to the end of the list
     *
     * @param NodeInterface $node
     * @return static
     */
    protected function append(NodeInterface $node): static
    {
        if ($this->removeParentNode($node)) {
            array_push($this->items, $node);
        }

        return $this;
    }

    /**
     * Remove a node from the list and reorder the indexes
     *
     * @param NodeInterface $node
     * @return static
     */
    protected function remove(NodeInterface $node): static
    {
        if (false !== $key = $this->indexOf($node)) {
            unset($this->items[$key]);

            $this->items = array_values($this->items);
        }

        return $this;
    }

    protected function validateItemType(mixed $item): void
    {
        if (!$item instanceof NodeInterface) {
            throw new InvalidNodeException(
                sprintf(InvalidNodeException::NODE_LIST_EXCEPTION, NodeInterface::class, gettype($item))
            );
        }
    }

    /**
     * This method ensures the node is not its own parent before removing the parent
     *
     * @param NodeInterface $node
     * @return boolean
     */
    private function removeParentNode(NodeInterface $node): bool
    {
        if ($node->getParentElement() !== $node) {
            $node->getParentElement()?->removeChild($node);
            return true;
        }

        return false;
    }
}
