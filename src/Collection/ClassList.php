<?php

namespace Ucscode\UssElement\Collection;

use Ucscode\UssElement\Exception\InvalidAttributeException;
use Ucscode\UssElement\Support\AbstractCollection;

/**
 * An instance of this class contains a list of class names for an element
 *
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
     * @return static
     */
    public function contains(string $value): bool
    {
        foreach ($this->splitClasses($value) as $class) {
            // `false` if the class does not exist
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
            /**
             * Use ternary operator (shortcut if/else)
             *
             * @see https://www.phptutorial.net/php-tutorial/php-ternary-operator/
             */
            in_array($class, $this->items) ? $this->remove($class) : $this->add($class);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function clear(): static
    {
        return parent::clear();
    }

    protected function validateItemType(mixed $item)
    {
        if (!$this->canBeString($item)) {
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
        // split the classes by space and trim all the values
        $classes = array_map('trim', explode(' ', $value));

        /**
         * remove empty classes (using arrow function)
         *
         * @see https://www.php.net/manual/en/functions.arrow.php
         */
        return array_filter($classes, fn (string $class) => !empty($class));
    }
}
