<?php

namespace Ucscode\PHPDocument\Parser\Engine;

use Ucscode\PHPDocument\Contracts\ElementInterface;

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/Attribute_selectors
 */
class AttributeMatcher
{
    public array $matches = [];

    /**
     * Undocumented function
     *
     * @param ElementInterface $node
     * @param array<string, string|null> $attributes values are base64 encoded
     */
    public function __construct(protected ElementInterface $node, protected array $attributes)
    {
        $this->validateNodeAgainstAttributes();
    }

    public function getMatches(): array
    {
        return $this->matches;
    }

    public function matchesNode(): bool
    {
        return !in_array(false, $this->matches, true);
    }

    protected function validateNodeAgainstAttributes(): void
    {
        foreach ($this->attributes as $key => $value) {

            /**
             * @var string $name (e.g data-name)
             * @var string $operator [~^$|*]
             */
            [$name, $operator] = $this->splitAttributeKey($key);

            // [attr] check if element has the attribute
            $this->matches[$name] = $this->node->hasAttribute($name);

            if ($value !== null) {
                if ($value !== '') {
                    $value = base64_decode($value, true) ?: $value;
                }

                if (empty($operator)) {
                    // [attr=value] verify that the 'selector value' equals the 'node attribute value'
                    $this->matches["{$name}=?"] = $this->node->getAttribute($name) === $value;

                    continue;
                }
            }

            if (!empty($operator)) {
                // get the node attribute value
                $attributeValue = $this->node->getAttribute($name);
                $pointer = "{$name}{$operator}=value";

                if ($attributeValue !== null) {
                    $this->matches[$pointer] = match($operator) {
                        '$' => str_ends_with($attributeValue, $value),
                        '^' => str_starts_with($attributeValue, $value),
                        '*' => str_contains($attributeValue, $value),
                        '~' => preg_match('/\b' . preg_quote($value, '/') . '\b/', $attributeValue), // Space-separated word match
                        '|' => preg_match('/(^|\|)' . preg_quote($value, '/') . '($|\|)/', $attributeValue), // Hyphen or exact match
                        default => false,
                    };

                    continue;
                }

                $this->matches[$pointer] = false;
            }
        }
    }

    public function splitAttributeKey(string $key): array
    {
        if (preg_match('/^([\w\-]+)([~^$*|]?)$/', $key, $matches)) {
            return [$matches[1], $matches[2]];
        }

        return [$key, ''];
    }
}
