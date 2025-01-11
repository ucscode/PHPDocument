<?php

namespace Ucscode\UssElement\Contracts;

/**
 * The base interface for all collection objects
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
interface CollectionInterface extends \IteratorAggregate, \Countable
{
    public function toArray(): array;
    public function isEmpty(): bool;
    public function sort(callable $callback): static;
    public function clear(): static;
}
