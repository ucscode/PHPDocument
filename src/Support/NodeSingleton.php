<?php

namespace Ucscode\UssElement\Support;

class NodeSingleton
{
    private static ?NodeSingleton $instance = null;
    private int $nextId = 0;

    /**
     * Return a single instance of this object shared between nodes for safe interaction
     *
     * @return NodeSingleton
     */
    public static function getInstance(): NodeSingleton
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
