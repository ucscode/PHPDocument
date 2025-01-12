<?php

namespace Ucscode\UssElement\Parser\Engine;

use Ucscode\UssElement\Parser\Collection\AttributeDtoCollection;
use Ucscode\UssElement\Parser\Dto\AttributeDto;
use Ucscode\UssElement\Parser\Enum\NodeQueryRegexpEnum;

/**
 * Tokenize a (non-recursive) selector
 *
 * Selector Rules
 *
 * - The tokenization can only be applied to a non-recursive element selector
 * - Siblings and descendants selectors are not prioritized
 * - The selector must be a single rule without space (because space represent descendant)
 * - The selector must not have combinator (because combinator represents descendant or sibliing)
 * - Attributes in the selector must be encoded in base64
 * - When the selector attributes are decoded, quoted value must also be in base64 encoding
 *
 * Valid Selector
 *
 * - (valid) node-name.class-name#id[base64-encoded-attr][another-base64-encoded-attr]
 *
 * Invalid Selector
 *
 * - (has combinator) node-name.class-name>#id+[base64-encoded-attr]
 * - (has space) node-name.class #id [base64-encoded-attr]
 * - (non encoded attribute) node-name#id[attr-name="sometext"]
 *
 * How to properly pass a selector to the tokenizer
 *
 * ```
 * $transformer = new Transformer();
 * $encodedQuotes = $transformer->encodeQuotedStrings('your-selector');
 * $fullyEncodedSelector = $transformer->encodeAttributes($encodedQuotes);
 * $tokenizer = new Tokenizer($fullyEncodedSelector);
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class Tokenizer
{
    protected string $selector;

    /**
     * @param string $fullyEncodedSelector Selector with base64 encoded attributes
     */
    public function __construct(string $fullyEncodedSelector)
    {
        $this->selector = trim($fullyEncodedSelector);
    }

    public function getTag(): ?string
    {
        preg_match(NodeQueryRegexpEnum::REGEXP_TAG->value, $this->selector, $matches);

        return $matches[0] ?? null;
    }

    public function getId(): ?string
    {
        preg_match(NodeQueryRegexpEnum::REGEXP_ID->value, $this->selector, $matches);

        return $matches[1] ?? null;
    }

    /**
     * @return array<int, string>
     */
    public function getClasses(): array
    {
        preg_match_all(NodeQueryRegexpEnum::REGEXP_CLASSES->value, $this->selector, $matches);

        return $matches[1] ?? [];
    }

    /**
     * Return a linear list of attributes string
     *
     * @return string[]
     */
    public function getAttributes(): array
    {
        return array_map(
            fn (string $value) => (new Transformer())->decodeQuotedStrings($value),
            $this->getDecodedAttributes()
        );
    }

    /**
     * Return a dto collection of attributes
     *
     * @return AttributeDtoCollection<string, AttributeDto>
     */
    public function getAttributeDtoCollection(): AttributeDtoCollection
    {
        $attributors = [];

        foreach ($this->getDecodedAttributes() as $attribute) {
            $attributor = new AttributeDto($attribute);
            $attributors[$attributor->getName()] = $attributor;
        }

        return new AttributeDtoCollection($attributors);
    }

    /**
     * @return array<int, string>
     */
    public function getPseudoClasses(): array
    {
        preg_match_all(NodeQueryRegexpEnum::REGEXP_PSEUDO_CLASSES->value, $this->selector, $matches);

        return $matches[1] ?? [];
    }

    /**
     * @return array<string, string>
     */
    public function getPseudoFunctions(): array
    {
        preg_match_all(NodeQueryRegexpEnum::REGEXP_PSEUDO_FUNCTIONS->value, $this->selector, $matches);

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
        preg_match_all(NodeQueryRegexpEnum::REGEXP_PSEUDO_ELEMENTS->value, $this->selector, $matches);

        return $matches[1] ?? [];
    }

    /**
     * Capture all attributes and decode each from base64 format
     *
     * @return string[]
     */
    private function getDecodedAttributes(): array
    {
        preg_match_all(NodeQueryRegexpEnum::REGEXP_ATTRIBUTES->value, $this->selector, $matches);

        // decode attributes from base64
        return array_map(
            fn (string $encoding) => base64_decode($encoding, true),
            $matches[1] ?? []
        );
    }
}
