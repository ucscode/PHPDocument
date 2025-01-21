<?php

namespace Ucscode\UssElement\Node;

use Ucscode\UssElement\Enums\NodeTypeEnum;
use Ucscode\UssElement\Abstraction\AbstractCharacterData;

/**
 * An object oriented representation of HTML comment
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class CommentNode extends AbstractCharacterData
{
    public function __construct(string $data = '')
    {
        parent::__construct();

        $this->nodeName = '#comment';
        $this->data = $data;
    }

    public function getNodeTypeEnum(): NodeTypeEnum
    {
        return NodeTypeEnum::NODE_COMMENT;
    }

    public function render(?int $indent = null): string
    {
        $comment = sprintf('<!--%s-->', $this->data);

        return $indent === null ? $comment : $this->indent($comment, max(0, abs($indent)), (bool) $indent);
    }
}
