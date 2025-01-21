<?php

namespace Ucscode\UssElement\Abstraction;

use Ucscode\UssElement\Contracts\CollectionInterface;

/**
 * @template TKey
 * @template TValue
 * @implements CollectionInterface<TKey, TValue>
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
abstract class AbstractCollection implements CollectionInterface
{
    abstract protected function validateItem(mixed $item): void;

    /**
     * @var array<TKey, TValue>
     */
    protected array $items;

    /**
     * @param array<TKey, TValue> $items
     */
    public function __construct(array $items = [])
    {
        $this->replaceItemsProperty($items);
    }

    /**
     * @return \Traversable<TKey, TValue>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Return the collection as an array
     *
     * @return array<TKey, TValue>
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
     * Iterate through each item on the collection
     *
     * @param callable $callback
     * @return static
     */
    public function each(callable $callback): static
    {
        foreach ($this->items as $key => $value) {
            call_user_func($callback, $value, $key);
        }

        return $this;
    }

    /**
     * Replace every item in the list
     *
     * @param array<TKey, TValue> $items
     * @return static
     */
    protected function replaceItemsProperty(array $items): static
    {
        foreach ($items as $item) {
            $this->validateItem($item);
        }

        $this->items = $items;

        return $this;
    }

    protected function isStringable(mixed $item): bool
    {
        return is_null($item) || is_scalar($item) || $item instanceof \Stringable;
    }
}
