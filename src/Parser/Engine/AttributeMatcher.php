<?php

namespace Ucscode\PHPDocument\Parser\Engine;

use Ucscode\PHPDocument\Contracts\ElementInterface;
use Ucscode\PHPDocument\Parser\Collection\AttributeDtoCollection;
use Ucscode\PHPDocument\Parser\Dto\AttributeDto;

/**
 * Compare selector attributes against a node to verify if the node attribute matches the attributes in the selector
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/Attribute_selectors
 * @internal Used internally by Matcher
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class AttributeMatcher
{
    public array $matches = [];

    /**
     * Undocumented function
     *
     * @param ElementInterface $node
     * @param AttributeDtoCollection<int, AttributeDto> $attributes values are base64 encoded
     */
    public function __construct(protected ElementInterface $node, protected AttributeDtoCollection $attributeDtoCollection)
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
        /**
         * @var AttributeDto $attributeDto Attribute properties from css selector
         */
        foreach ($this->attributeDtoCollection as $attributeDto) {
            $name = $attributeDto->getName();
            $operator = $attributeDto->getOperator();

            // [attr] check if element has the attribute
            $this->matches[$name] = $this->node->hasAttribute($name);

            $value = $attributeDto->getValue();
            $attributeValue = $this->node->getAttribute($name);

            if ($value !== null) {
                if ($attributeDto->isCaseInSensitive()) {
                    $value = strtolower($value);

                    if ($attributeValue !== null) {
                        $attributeValue = strtolower($attributeValue);
                    }
                }

                if (empty($operator)) {
                    // [attr=value] verify that the 'selector value' equals the 'node attribute value'
                    $this->matches["{$name}=?"] = $attributeValue === $value;

                    continue;
                }
            }

            if (!empty($operator)) {
                // a pointer for debugging
                $pointer = "{$name}{$operator}=value";

                if (null !== $attributeValue) {
                    // verify that value matches the operator
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
}
