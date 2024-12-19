<?php

namespace Ucscode\PHPDocument\Support;

/**
 * @author Name <email@email.com>
 */
abstract class AbstractCharacterData extends AbstractNode
{
    protected string $data = '';

    public function getLength(): int
    {
        return strlen($this->data);
    }
}
