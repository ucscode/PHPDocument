<?php

namespace Ucscode\UssElement\Node;

use Ucscode\UssElement\Enums\NodeNameEnum;
use Ucscode\UssElement\Enums\NodeTypeEnum;
use Ucscode\UssElement\Abstraction\AbstractNode;

/**
 * An object oriented representation of HTML doctype
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class DocumentTypeNode extends AbstractNode
{
    public function __construct(string|NodeNameEnum $nodeName)
    {
        parent::__construct();

        $this->nodeName = $nodeName instanceof NodeNameEnum ? $nodeName->value : $nodeName;
    }

    public function getNodeTypeEnum(): NodeTypeEnum
    {
        return NodeTypeEnum::NODE_DOCUMENT_TYPE;
    }

    public function render(?int $indent = null): string
    {
        $doctype = sprintf('<!DOCTYPE %s>', $this->nodeName);

        return $indent === null ? $doctype : $this->indent($doctype, max(0, abs($indent)), (bool) $indent);
    }
}
