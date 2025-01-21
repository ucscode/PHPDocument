<?php

namespace Ucscode\UssElement\Support;

use Ucscode\UssElement\Contracts\DocumentInterface;
use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Exception\DOMException;

final class Assertion
{
    public static function isNodeInsertable(NodeInterface $parent, NodeInterface $child): void
    {
        if (!($parent instanceof ElementInterface || $parent instanceof DocumentInterface)) {
            throw new DOMException(DOMException::HIERARCHY_REQUEST_ERR);
        }

        if ($parent === $child) {
            throw new DOMException(DOMException::HIERARCHY_REQUEST_ERR);
        }

        foreach ($parent->getParentElements() as $ancestor) {
            if ($ancestor === $child) {
                throw new DOMException(DOMException::HIERARCHY_REQUEST_ERR);
            }
        }
    }

    public static function isSiblingMovable(NodeInterface $target, NodeInterface $sibling): void
    {
        $movable = $sibling->getParentNode() !== null
            && $sibling->getParentNode() === $target->getParentNode()
            && ($sibling->getParentNode() instanceof ElementInterface || $sibling->getParentNode() instanceof DocumentInterface)
        ;

        if (!$movable) {
            throw new DOMException(DOMException::HIERARCHY_REQUEST_ERR);
        }
    }
}