<?php

namespace Ucscode\UssElement\Support;

use Ucscode\UssElement\Abstraction\AbstractCollection;

/**
 * @template TKey of int|string
 * @template TValue
 * @extends AbstractCollection<TKey, TValue>
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class Collection extends AbstractCollection
{
    /**
     * Set or update an item
     *
     * @param string $name
     * @param mixed $value
     * @return static
     */
    public function set(string $name, mixed $value): static
    {
        $this->items[$name] = $value;

        return $this;
    }

    /**
     * Get an item value or return a default
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get(string $name, mixed $default = null): mixed
    {
        return $this->items[$name] ?? $default;
    }

    /**
     * Check if the key exists
     *
     * @param string $name
     * @return boolean
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->items);
    }

    /**
     * Check if a value exists with the list of items
     *
     * @param mixed $value
     * @return boolean
     */
    public function contains(mixed $value): bool
    {
        return in_array($value, $this->items, true);
    }

    /**
     * Remove an item from the collection
     *
     * @param string $name
     * @return static
     */
    public function remove(string $name): static
    {
        if ($this->has($name)) {
            unset($this->items[$name]);
        }

        return $this;
    }

    /**
     * Get a list of item keys
     *
     * @return array<string|int>
     */
    public function keys(): array
    {
        return array_keys($this->items);
    }

    /**
     * Get a list of item values
     *
     * @return array<mixed>
     */
    public function values(): array
    {
        return array_values($this->items);
    }

    /**
     * Map the items and return an instance of the collection
     *
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback): static
    {
        // @phpstan-ignore-next-line
        return new static(array_map($callback, $this->items));
    }

    /**
     * Filter items and return instance of the collection
     *
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback): static
    {
        // @phpstan-ignore-next-line
        return new static(array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH));
    }

    protected function validateItem(mixed $item): void
    {
        //
    }
}
