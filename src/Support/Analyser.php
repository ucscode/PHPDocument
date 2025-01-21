<?php

namespace Ucscode\UssElement\Support;

use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Enums\NodeTypeEnum;

final class Analyser
{
    public function canHaveChildNode(NodeInterface $parentNode): bool
    {
        return in_array($parentNode->getNodeType(), [
            NodeTypeEnum::NODE_ELEMENT->value,
            NodeTypeEnum::NODE_DOCUMENT->value,
            // NodeTypeEnum::NODE_DOCUMENT_FRAGMENT->value,
        ], true);
    }

    // verify that target & node are siblings and can be moved
    public function isSiblingAdjustable(NodeInterface $target, NodeInterface $node): bool
    {
        return $node->getParentNode() !== null
            && $node->getParentNode() === $target->getParentNode()
            && $this->canHaveChildNode($node->getParentNode())
        ;
    }
}