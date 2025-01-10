<?php

namespace Ucscode\UssElement\Support;

use Ucscode\UssElement\Contracts\CollectionInterface;

/**
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
abstract class AbstractCollection implements CollectionInterface
{
    /**
     * Validate the integrity of an item to retain an array of static types
     *
     * @param mixed $item The item to validate
     */
    abstract protected function validateItemType(mixed $item);

    public function __construct(protected array $items = [])
    {
        foreach ($this->items as $item) {
            $this->validateItemType($item);
        }
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * Return the collection as an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * Returns a boolean indicating whether the items is empty or not
     *
     * @return boolean
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Sort the items using a custom defined function
     *
     * @param callable $callback
     * @return static
     */
    protected function sort(callable $callback): static
    {
        usort($this->items, $callback);

        return $this;
    }

    /**
     * Clear the entire items of the collection
     *
     * @return static
     */
    protected function clear(): static
    {
        $this->items = [];

        return $this;
    }

    protected function canBeString(mixed $item): bool
    {
        return is_null($item) || is_scalar($item) || $item instanceof \Stringable;
    }
}
