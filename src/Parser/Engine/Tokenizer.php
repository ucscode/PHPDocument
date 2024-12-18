<?php

namespace Ucscode\PHPDocument\Parser\Engine;

class Tokenizer
{
    protected string $selector;

    /**
     * @param string $selector Attribute values (or quoted strings) must be encoded to base64
     */
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

    /**
     * @return array<int, string>
     */
    public function getClasses(): array
    {
        preg_match_all('/(?<!\()\.([a-z0-9_-]+)/i', $this->selector, $matches);

        return $matches[1] ?? [];
    }

    /**
     * @param boolean $explode Transform attributes to key/value pairs
     * @return array<int|string, string|null>
     */
    public function getAttributes(bool $explode = false): array
    {
        preg_match_all('/\[([^\]]+)\]/', $this->selector, $matches);

        $result = $matches[1] ?? [];

        return $explode && !empty($result) ? $this->keyValueAttributes($result) : $result;
    }

    /**
     * @return array<int, string>
     */
    public function getPseudoClasses(): array
    {
        preg_match_all('/(?<!:):([a-z-]+)(?!\()/i', $this->selector, $matches);

        return $matches[1] ?? [];
    }

    /**
     * @return array<string, string>
     */
    public function getPseudoFunctions(): array
    {
        preg_match_all('/:([a-z-]+)\(([^\)]+)\)/i', $this->selector, $matches);

        if (!empty($matches[1])) {
            return array_combine($matches[1], $matches[2]);
        }

        return [];
    }

    /**
     * @return array<int, string>
     */
    public function getPseudoElements(): array
    {
        preg_match_all('/::([a-z-]+)/i', $this->selector, $matches);

        return $matches[1] ?? [];
    }

    /**
     * @param array<int, string> $attributes
     * @return array<string, string|null>
     */
    private function keyValueAttributes(array $attributes): array
    {
        $keyValues = [];

        foreach ($attributes as $attribute) {
            $segment = explode('=', $attribute);
            $key = $segment[0];
            $value = $segment[1] ?? null;
            $keyValues[$key] = ($value === '' || $value === null) ? null : trim($value, "'\"");
        }

        return $keyValues;
    }
}
