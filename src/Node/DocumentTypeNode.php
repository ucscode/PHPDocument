<?php

namespace Ucscode\UssElement\Node;

use Ucscode\UssElement\Enums\NodeTypeEnum;
use Ucscode\UssElement\Support\AbstractNode;

/**
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class DocumentTypeNode extends AbstractNode
{
    public function getNodeType(): int
    {
        return NodeTypeEnum::NODE_DOCUMENT_TYPE->value;
    }

    public function render(?int $indent = null): string
    {
        return sprintf('<!DOCTYPE %s>%s', $this->nodeName, $indent !== null ? "\n" : '');
    }
}
