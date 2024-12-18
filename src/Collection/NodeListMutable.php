<?php

namespace Ucscode\PHPDocument\Collection;

use Ucscode\PHPDocument\Contracts\NodeInterface;

/**
 * A mutable version of nodelist
 *
 * @author Name <email@email.com>
 */
class NodeListMutable extends NodeList
{
    /**
     * Insert the given node at a specific position within the list
     *
     * @param integer $index
     * @param NodeInterface $node
     * @return static
     */
    public function insertAt(int $index, NodeInterface $node): static
    {
        if ($this->mutateNode($node)) {
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
    public function prepend(NodeInterface $node): static
    {
        if ($this->mutateNode($node)) {
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
    public function append(NodeInterface $node): static
    {
        if ($this->mutateNode($node)) {
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
    public function remove(NodeInterface $node): static
    {
        if (false !== $key = array_search($node, $this->items)) {
            unset($this->items[$key]);

            $this->items = array_values($this->items);
        }

        return $this;
    }

    private function mutateNode(NodeInterface $node): bool
    {
        if ($node->getParentElement() !== $node) {
            $node->getParentElement()?->removeChild($node);
            return true;
        }

        return false;
    }
}
