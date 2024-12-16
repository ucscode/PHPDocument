<?php

namespace Ucscode\PHPDocument\Node;

use Ucscode\PHPDocument\Enums\NodeTypeEnum;
use Ucscode\PHPDocument\Support\AbstractNode;

class TextNode extends AbstractNode
{
    protected string $value = '';

    public function __construct(string $text = '')
    {
        parent::__construct('#text');

        $this->value = $text;
    }

    public function render(): string
    {
        return $this->value;
    }

    public function getNodeType(): int
    {
        return NodeTypeEnum::NODE_TEXT->value;
    }
}
