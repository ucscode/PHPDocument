<?php

namespace Ucscode\PHPDocument\Node;

use Ucscode\PHPDocument\Enums\NodeTypeEnum;
use Ucscode\PHPDocument\Support\AbstractNode;

/**
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class TextNode extends AbstractNode
{
    protected string $value = '';

    public function __construct(string $text = '')
    {
        parent::__construct('#text');

        $this->value = $text;
    }

    public function render(?int $indent = null): string
    {
        $text = $this->value;

        if ($indent !== null) {
            $text = $this->indent($this->value, max(0, $indent));
        }

        return $text;
    }

    public function getNodeType(): int
    {
        return NodeTypeEnum::NODE_TEXT->value;
    }
}
