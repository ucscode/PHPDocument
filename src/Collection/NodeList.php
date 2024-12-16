<?php

namespace Ucscode\PHPDocument\Collection;

use Ucscode\PHPDocument\Contracts\NodeInterface;
use Ucscode\PHPDocument\Support\AbstractCollection;

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
     * Verify that the specified node is first in the list
     *
     * @param NodeInterface $node
     * @return boolean
     */
    public function isFirst(NodeInterface $node): bool
    {
        return $this->indexOf($node) === 0;
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
     * Verify that the specified node is last in the list
     *
     * @param NodeInterface $node
     * @return boolean
     */
    public function isLast(NodeInterface $node): bool
    {
        return $this->indexOf($node) === ($this->count() - 1);
    }

    /**
     * Insert the given node at a specific position within the list
     *
     * @param integer $index
     * @param NodeInterface $node
     * @return static
     */
    public function insertAt(int $index, NodeInterface $node): static
    {
        if ($this->exists($node)) {
            $this->remove($node);
        }

        array_splice($this->items, $index, 0, $node);

        return $this;
    }

    /**
     * Add a node to the beginning of the list
     *
     * @param NodeInterface $node
     * @return static
     */
    public function prepend(NodeInterface $node): static
    {
        if ($this->exists($node) && !$this->isFirst($node)) {
            $this->remove($node);
        }

        array_unshift($this->items, $node);

        return $this;
    }

    /**
     * Add a node to the end of the list
     *
     * @param NodeInterface $node
     * @return static
     */
    public function append(NodeInterface $node): static
    {
        if ($this->exists($node) && !$this->isLast($node)) {
            $this->remove($node);
        }

        array_push($this->items, $node);

        return $this;
    }

    /**
     * Remove a node from the list and reorder the indexes
     *
     * @param NodeInterface $node
     * @return static
     */
    public function remove(NodeInterface $node): static
    {
        if (false !== $key = array_search($node, $this->items)) {
            unset($this->items[$key]);

            $this->items = array_values($this->items);
        }

        return $this;
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
}
