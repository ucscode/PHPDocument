<?php

namespace Ucscode\UssElement\Collection;

use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Exception\InvalidNodeException;

/**
 * An instance of this class contains items that implement the ElementInterface
 *
 * @template T
 * @implements IteratorAggregate<int, ElementInterface>
 * @property ElementInterface[] $items
 * @method ?ElementInterface get(int $index)
 * @method ?ElementInterface first()
 * @method ?ElementInterface last()
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class ElementList extends NodeList
{
    protected function validateItemType(mixed $item): void
    {
        if (!$item instanceof ElementInterface) {
            throw new InvalidNodeException(
                sprintf(InvalidNodeException::HTML_COLLECTION_EXCEPTION, ElementInterface::class, gettype($item))
            );
        }
    }
}
