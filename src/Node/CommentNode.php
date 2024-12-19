<?php

namespace Ucscode\PHPDocument\Node;

use Ucscode\PHPDocument\Enums\NodeTypeEnum;
use Ucscode\PHPDocument\Support\AbstractCharacterData;

/**
 * @author Name <email@email.com>
 */
class CommentNode extends AbstractCharacterData
{
    public function __construct(string $data = '')
    {
        parent::__construct('#comment');

        $this->data = $data;
    }

    public function getNodeType(): int
    {
        return NodeTypeEnum::NODE_COMMENT->value;
    }

    public function render(?int $indent = null): string
    {
        return $this->indent(sprintf('<!--%s-->', $this->data), max(0, $indent === null ? 0 : $indent), (bool) $indent);
    }
}
