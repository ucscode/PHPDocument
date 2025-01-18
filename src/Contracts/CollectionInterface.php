<?php

namespace Ucscode\UssElement\Contracts;

/**
 * The base interface for all collection objects
 *
 * @template TKey
 * @template TValue
 * @extends \IteratorAggregate<TKey, TValue>
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
interface CollectionInterface extends \IteratorAggregate, \Countable
{
    /**
     * @return array<TKey, TValue>
     */
    public function toArray(): array;
    public function isEmpty(): bool;
    public function sort(callable $callback): static;
}
