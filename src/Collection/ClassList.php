<?php

namespace Ucscode\UssElement\Collection;

use Ucscode\UssElement\Exception\InvalidAttributeException;
use Ucscode\UssElement\Abstraction\AbstractCollection;

/**
 * An instance of this class contains a list of class names for an element
 *
 * @extends AbstractCollection<int, string>
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
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
        foreach ($this->splitClasses($value) as $class) {
            if (!in_array($class, $this->items)) {
                $this->items[] = $class;
            }
        }

        return $this;
    }

    /**
     * Remove a class from the item if it exists
     *
     * @param string $value
     * @return static
     */
    public function remove(string $value): static
    {
        foreach ($this->splitClasses($value) as $class) {
            $this->items = array_filter($this->items, fn (string $item) => $item !== $class);
        }

        return $this;
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
        $this->remove($previous); // remove previous class
        $this->add($new); // add new class

        return $this;
    }

    /**
     * Check if a class exists
     *
     * @param string $value
     * @return bool
     */
    public function contains(string $value): bool
    {
        foreach ($this->splitClasses($value) as $class) {
            if (!in_array($class, $this->items)) {
                return false;
            }
        }

        return true;
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
        foreach ($this->splitClasses($value) as $class) {
            in_array($class, $this->items) ? $this->remove($class) : $this->add($class);
        }

        return $this;
    }

    /**
     * Remove all element from the class list
     *
     * @return static
     */
    public function clear(): static
    {
        $this->items = [];

        return $this;
    }

    protected function validateItem(mixed $item): void
    {
        if (!$this->isStringable($item)) {
            throw new InvalidAttributeException(
                sprintf(InvalidAttributeException::CLASS_ATTRIBUTE_EXCEPTION, gettype($item))
            );
        }
    }

    /**
     * Return an array of non-empty classes
     *
     * @param string $value
     * @return array<int, string>
     */
    private function splitClasses(string $value): array
    {
        $classes = array_map('trim', explode(' ', $value));

        return array_filter($classes, fn (string $class) => !empty($class));
    }
}
