<?php

namespace Ucscode\UssElement\Abstract;

use Exception;
use Ucscode\UssElement\Interface\UssElementInterface;

abstract class AbstractUssElementChildCatalyst extends AbstractUssElementParser
{
    /**
     * Appends a child element to the current element.
     *
     * @param UssElementInterface $child The child element to append.
     * @return self
     */
    public function appendChild(UssElementInterface $child): self
    {
        $child = $this->inspectChild($child, __METHOD__);
        $this->children[] = $child;
        return $this;
    }

    /**
     * Prepends a child element to the current element.
     *
     * @param UssElementInterface $child The child element to prepend.
     * @return self
     */
    public function prependChild(UssElementInterface $child): self
    {
        $child = $this->inspectChild($child, __METHOD__);
        array_unshift($this->children, $child);
        return $this;
    }

    /**
     * Inserts a child element before a specified reference element.
     *
     * @param UssElementInterface $child The child element to insert.
     * @param UssElementInterface $reference The reference element before which the child will be inserted.
     * @return void
     */
    public function insertBefore(UssElementInterface $child, UssElementInterface $reference): self
    {
        return $this->insertAtReferenceIndex($child, $reference, 0);
    }

    /**
     * Inserts a child element after a specified reference element.
     *
     * @param UssElementInterface $child The child element to insert.
     * @param UssElementInterface $reference The reference element after which the child will be inserted.
     * @return void
     */
    public function insertAfter(UssElementInterface $child, UssElementInterface $reference): self
    {
        return $this->insertAtReferenceIndex($child, $reference, 1);
    }

    /**
     * Replaces a child element with another element.
     *
     * @param UssElementInterface $child The new child element to replace the reference element.
     * @param UssElementInterface $reference The reference element to be replaced.
     * @return void
     */
    public function replaceChild(UssElementInterface $child, UssElementInterface $reference): self
    {
        $key = array_search($reference, $this->children, true);
        if ($key !== false) {
            $child = $this->inspectChild($child, __METHOD__);
            $this->children[$key] = $child;
        }
        return $this;
    }

    /**
     * Returns the first child element of the current element.
     *
     * @return UssElementInterface|null The first child element as a UssElementInterface object, or null if there are no children.
     */
    public function getFirstChild(): ?UssElementInterface
    {
        return $this->children[0] ?? null;
    }

    /**
     * Returns the last child element of the current element.
     *
     * @return UssElementInterface|null The last child element as a UssElementInterface object, or null if there are no children.
     */
    public function getLastChild(): ?UssElementInterface
    {
        $index = count($this->children) - 1;
        return $this->children[$index] ?? null;
    }

    /**
     * Returns a child element at a specified index.
     *
     * @param int $index The index of the child element to retrieve.
     * @return UssElementInterface|null The child element as a UssElementInterface object, or null if the index is out of bounds.
     */
    public function getChild(int $index): ?UssElementInterface
    {
        return $this->children[$index] ?? null;
    }

    /**
     * Removes a child element from the current element.
     *
     * @param UssElementInterface $child The child element to remove.
     * @return void
     */
    public function removeChild(UssElementInterface $child): void
    {
        $key = array_search($child, $this->children, true);
        if ($key !== false) {
            unset($this->children[$key]);
            $this->children = array_values($this->children);
        };
    }

    /**
     * @method sortChildren
     */
    public function sortChildren(callable $callback): void
    {
        usort($this->children, $callback);
    }

    /**
     * Change Position of element by adding it after or before a reference
     *
     * @param UssElementInterface $child - The element to move above or below a target
     * @param UssElementInterface $reference - The targeted element
     * @param integer $index - 0 to move before, 1 to move after
     */
    protected function insertAtReferenceIndex(UssElementInterface $child, UssElementInterface $reference, int $index = 0): self
    {
        if (array_search($reference, $this->children, true) !== false) {
            $child = $this->inspectChild($child, __METHOD__);
            $key = array_search($reference, $this->children, true);
            array_splice($this->children, ($key + $index), 0, [$child]);
            $this->children = array_values($this->children);
        }
        return $this;
    }

    /**
     * @ignore
     */
    protected function inspectChild(AbstractUssElementFoundation $child, string $method): UssElementInterface
    {
        if ($this === $child) {
            $errorContext = sprintf("Trying to add self as child in %s", $method);
            throw new Exception($errorContext);
        };
        $key = array_search($child, $this->children, true);
        if ($key !== false) {
            array_splice($this->children, $key, 1);
            $this->children = array_values($this->children);
        };
        $child->setParent($this);
        return $child;
    }
}
