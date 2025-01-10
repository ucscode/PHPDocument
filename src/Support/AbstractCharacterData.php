<?php

namespace Ucscode\UssElement\Support;

use Ucscode\UssElement\Contracts\NodeInterface;

/**
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
abstract class AbstractCharacterData extends AbstractNode
{
    protected string $data = '';

    public function getLength(): int
    {
        return strlen($this->data);
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function cloneNode(bool $deep = false): NodeInterface
    {
        return (new static($this->data))->setVisible($this->visible);
    }
}
