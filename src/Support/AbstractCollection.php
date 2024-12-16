<?php

namespace Ucscode\PHPDocument\Support;

use Ucscode\PHPDocument\Contracts\CollectionInterface;

abstract class AbstractCollection implements CollectionInterface
{
    public function __construct(protected array $items = [])
    {

    }

    /**
     * Return the same values with a different wrapper
     *
     * @return static
     */
    public function getReplica(): static
    {
        return new static($this->items);
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
     * Clear the entire items of the collection
     *
     * @return static
     */
    public function clear(): static
    {
        $this->items = [];

        return $this;
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
    public function sort(callable $callback): static
    {
        usort($this->items, $callback);

        return $this;
    }

    /**
     * Return a mapped version of the collection
     *
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback): static
    {
        return new static(array_map($callback, $this->items));
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetUnset(mixed $offset): void
    {
        if (array_keys($offset, $this->items)) {
            unset($this->items[$offset]);
        }
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->items[$offset] = $value;
    }
}
