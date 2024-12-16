<?php

namespace Ucscode\PHPDocument\Node;

use Ucscode\PHPDocument\Enums\NodeTypeEnum;
use Ucscode\PHPDocument\Support\AbstractNode;

class TextNode extends AbstractNode
{
    protected string $value = '';

    public function __construct()
    {
        parent::__construct('#text');
    }

    public function render(): string
    {
        return $this->value;
    }

    public function getNodeType(): int
    {
        return NodeTypeEnum::TEXT_NODE->value;
    }
}
