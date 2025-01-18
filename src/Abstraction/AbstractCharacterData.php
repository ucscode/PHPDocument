<?php

namespace Ucscode\UssElement\Abstraction;

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
}
