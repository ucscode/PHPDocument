<?php

namespace Ucscode\PHPDocument\Collection;

use Ucscode\PHPDocument\Support\AbstractCollection;

class ClassList extends AbstractCollection implements \Stringable
{
    public function __toString(): string
    {
        return implode(' ', $this->items);
    }

    /**
     * Add a class to the items if it does not exist
     *
     * @param string $value
     * @return static
     */
    public function add(string $value): static
    {

    }

    /**
     * Remove a class from the item if it exists
     *
     * @param string $value
     * @return static
     */
    public function remove(string $value): static
    {

    }

    /**
     * Replace an existing class with a new one
     *
     * If the previous class does not exists, add a new one
     *
     * @param string $previous
     * @param string $new
     * @return static
     */
    public function replace(string $previous, string $new): static
    {

    }

    /**
     * Check if a class exists
     *
     * @param string $value
     * @return static
     */
    public function contains(string $value): static
    {

    }

    /**
     * Toggle a class
     *
     * If the class exists, remove it, otherwise, add it
     *
     * @param string $value
     * @return static
     */
    public function toggle(string $value): static
    {

    }

    protected function validateItemType(mixed $item)
    {

    }
}
