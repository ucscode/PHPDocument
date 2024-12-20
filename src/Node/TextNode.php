<?php

namespace Ucscode\UssElement\Node;

use Ucscode\UssElement\Enums\NodeTypeEnum;
use Ucscode\UssElement\Support\AbstractCharacterData;

/**
 * An object oriented representation of HTML text
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class TextNode extends AbstractCharacterData
{
    public function __construct(string $data = '')
    {
        parent::__construct('#text');

        $this->data = $data;
    }

    public function render(?int $indent = null): string
    {
        return $indent === null ? $this->data : $this->indent($this->data, max(0, abs($indent)));
    }

    public function getNodeType(): int
    {
        return NodeTypeEnum::NODE_TEXT->value;
    }

    public function isContentWhiteSpace(): bool
    {
        return trim($this->data) === '';
    }
}
