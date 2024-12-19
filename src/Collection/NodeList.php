<?php

namespace Ucscode\UssElement\Collection;

use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Exception\InvalidNodeException;
use Ucscode\UssElement\Support\AbstractCollection;

/**
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

    protected function validateItemType(mixed $item): void
    {
        if (!$item instanceof NodeInterface) {
            throw new InvalidNodeException(
                sprintf(InvalidNodeException::NODE_LIST_EXCEPTION, NodeInterface::class, gettype($item))
            );
        }
    }
}
