<?php

namespace Ucscode\UssElement\Collection;

use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Exception\InvalidNodeException;
use Ucscode\UssElement\Abstraction\AbstractCollection;
use Ucscode\UssElement\Contracts\ElementInterface;

/**
 * An instance of this class contains items that implement the NodeInterface
 *
 * @template TKey of int
 * @template TValue of NodeInterface|ElementInterface
 * @extends AbstractCollection<TKey, TValue>
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
     * Insert the given node at a specific position within the NodeList
     *
     * @param integer $index
     * @param NodeInterface $node
     * @return bool
     */
    protected function insertAt(int $index, NodeInterface $node): bool
    {
        if ($this->detachParentNode($node)) {
            array_splice($this->items, $index, 0, [$node]);

            return in_array($node, $this->items, true);
        }

        return false;
    }

    /**
     * Add a node to the beginning of the NodeList
     *
     * @param NodeInterface $node
     * @return bool
     */
    protected function prepend(NodeInterface $node): bool
    {
        if ($this->detachParentNode($node)) {
            array_unshift($this->items, $node);

            return in_array($node, $this->items, true);
        }

        return false;
    }

    /**
     * Add a node to the end of the NodeList
     *
     * @param NodeInterface $node
     * @return bool
     */
    protected function append(NodeInterface $node): bool
    {
        if ($this->detachParentNode($node)) {
            array_push($this->items, $node);

            return in_array($node, $this->items, true);
        }

        return false;
    }

    /**
     * Remove a node from the NodeList and reorder the indexes
     *
     * @param NodeInterface $node
     * @return bool
     */
    protected function remove(NodeInterface $node): bool
    {
        $index = $this->indexOf($node);

        if ($index !== false) {
            unset($this->items[$index]);
            
            // reset item indexes
            $this->items = array_values($this->items);

            return true;
        }

        return false;
    }

    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof NodeInterface) {
            throw new InvalidNodeException(
                sprintf(InvalidNodeException::NODE_LIST_EXCEPTION, NodeInterface::class, gettype($item))
            );
        }
    }

    /**
     * Detach the node from it's previous parent element
     *
     * @param NodeInterface $node
     * @return bool
     */
    private function detachParentNode(NodeInterface $node): bool
    {
        // if node has no parent
        if ($node->getParentNode()) {
            return !!$node->getParentNode()->removeChild($node);
        }
        
        return true;
    }
}
