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
        $doctype = sprintf('<!DOCTYPE %s>', $this->nodeName);
        
        return $indent === null ? $doctype : $this->indent($doctype, max(0, abs($indent)), (bool) $indent);
    }
}
