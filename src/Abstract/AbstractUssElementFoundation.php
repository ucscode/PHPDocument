<?php

namespace Ucscode\UssElement\Abstract;

use Ucscode\UssElement\Interface\UssElementInterface;
use Ucscode\UssElement\Interface\UssElementNodeListInterface;

abstract class AbstractUssElementFoundation implements UssElementInterface, UssElementNodeListInterface
{
    public readonly string $tagName;
    public readonly string $nodeName;
    protected array $attributes = [];
    protected array $children;
    protected ?string $content;
    protected bool $void = false;
    protected bool $invisible = false;
    protected ?UssElementInterface $parentElement = null;

    protected $voidTags = [
        self::NODE_AREA,
        self::NODE_BASE,
        self::NODE_BR,
        self::NODE_COL,
        self::NODE_EMBED,
        self::NODE_HR,
        self::NODE_IMG,
        self::NODE_INPUT,
        self::NODE_LINK,
        self::NODE_META,
        self::NODE_PARAM,
        self::NODE_SOURCE,
        self::NODE_TRACK,
        self::NODE_WBR,
    ];

    public function __construct(string $nodeName)
    {
        $this->transformElement($nodeName);
        $this->resetContext();
    }

    public function transformElement(string $nodeName): self
    {
        $this->nodeName = strtoupper(trim($nodeName));
        $this->tagName = $this->nodeName;
        $this->void = in_array($this->nodeName, $this->voidTags);
        return $this;
    }

    public function resetContext(): self
    {
        $this->children = [];
        $this->content = null;
        return $this;
    }
    
    protected function setParent(UssElementInterface $parent): void
    {
        $this->parentElement = $parent;
    }

    protected function normalizeAttribute(?string $value = null): array
    {
        $attributes = array_map('trim', explode(' ', $value ??= ''));
        $value = array_filter($attributes, fn ($value) => $value !== '');
        return $value;
    }
}