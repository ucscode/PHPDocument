<?php

namespace Ucscode\PHPDocument\Collection;

use Ucscode\PHPDocument\Contracts\ElementInterface;
use Ucscode\PHPDocument\Exception\InvalidNodeException;

/**
 * @template T
 * @implements IteratorAggregate<int, ElementInterface>
 * @property ElementInterface[] $items
 */
class HtmlCollection extends NodeList
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
