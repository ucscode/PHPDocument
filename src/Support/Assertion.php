<?php

namespace Ucscode\UssElement\Support;

use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Exception\DOMException;

final class Assertion
{
    protected Analyser $analyser;

    public function __construct()
    {
        $this->analyser = new Analyser();
    }

    public function assertNodeCanHaveChild(?NodeInterface $target): void
    {
        if ($target === null) {
            throw new DOMException(
                DOMException::NOT_FOUND_ERR,
                "Cannot add child node to object of type null"
            );
        }

        if (!$this->analyser->canHaveChildNode($target)) {
            throw new DOMException(
                DOMException::HIERARCHY_REQUEST_ERR,
                sprintf("Cannot add a child node to a parent node of type %s", $target->getNodeTypeEnum()->getLabel())
            );
        }
    }

    public function assertNodesNotEqual(NodeInterface $target, NodeInterface $node): void
    {
        if ($target === $node) {
            throw new DOMException(
                DOMException::HIERARCHY_REQUEST_ERR,
                "Cannot add a node as its own child"
            );
        }
    }

    public function assertChildNodeIsNotAncestor(NodeInterface $target, NodeInterface $child): void
    {
        if ($target->getParentNodes()->exists($child)) {
            throw new DOMException(DOMException::HIERARCHY_REQUEST_ERR);
        }
    }

    public function assertChildExists(NodeInterface $target, NodeInterface $child): void
    {
        if (!$target->hasChild($child)) {
            throw new DOMException(DOMException::NOT_FOUND_ERR);
        }
    }

    public function canChangeSiblingsPosition(NodeInterface $target, NodeInterface $sibling): void
    {
        if (!$this->analyser->isSiblingAdjustable($target, $sibling)) {
            throw new DOMException(DOMException::HIERARCHY_REQUEST_ERR);
        }
    }

    public function assertCanAcceptChildNode(?NodeInterface $target, NodeInterface $child): void
    {
        $this->assertNodeCanHaveChild($target);
        $this->assertNodesNotEqual($target, $child);
        $this->assertChildNodeIsNotAncestor($target, $child);
    }
}