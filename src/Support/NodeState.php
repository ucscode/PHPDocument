<?php

namespace Ucscode\UssElement\Support;

final class NodeState
{
    private static ?self $instance = null;
    private int $nextId = 0;

    /**
     * Return a single instance of this object shared between nodes for safe interaction
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getNextId(): int
    {
        return $this->nextId++;
    }

    private function __construct()
    {
    }
}
