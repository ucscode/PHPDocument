<?php

namespace Ucscode\PHPDocument\Parser\Codec;

class Tokenizer
{
    protected string $selector;
    protected array $tokens = [];

    public function __construct(string $selector)
    {
        $this->selector = trim($selector);
    }

    public function getTag(): ?string
    {
        preg_match('/^[a-z]+[a-z0-9-]*/i', $this->selector, $matches);

        return $matches[0] ?? null;
    }

    public function getId(): ?string
    {
        preg_match('/#([a-z0-9_-]+)/i', $this->selector, $matches);

        return $matches[1] ?? null;
    }

    public function getClasses(): array
    {
        preg_match_all('/(?<!\()\.([a-z0-9_-]+)/i', $this->selector, $matches);

        return $matches[1] ?? [];
    }

    public function getAttributes(): array
    {
        preg_match_all('/\[([^\]]+)\]/', $this->selector, $matches);

        return $matches[1] ?? [];
    }

    public function getPseudoSelectors(): array
    {
        preg_match_all('/(?<!:):([a-z-]+)(?!\()/i', $this->selector, $matches);

        return $matches[1] ?? [];
    }

    public function getPseudoFunctions(): array
    {
        preg_match_all('/:([a-z-]+)\(([^\)]+)\)/i', $this->selector, $matches);

        if (!empty($matches[1])) {
            return array_combine($matches[1], $matches[2]);
        }

        return [];
    }

    public function getPseudoElements(): array
    {
        preg_match_all('/::([a-z-]+)/i', $this->selector, $matches);

        return $matches[1] ?? [];
    }
}
