<?php

namespace Ucscode\UssElement\Parser\Dto;

use Ucscode\UssElement\Parser\Engine\Transformer;

/**
 * A data transfer object for attributes parsed from a selector
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class AttributeDto
{
    protected string $name;
    protected ?string $operator = null;
    protected ?string $value = null;
    protected ?string $suffix = null;

    public function __construct(protected string $attribute)
    {
        $this->preprocessAttributes($attribute);
    }

    /**
     * The name of the attribute
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * The operator of the attribute
     *
     * Can be one of the following: "~", "|", "^", "$", "*"
     *
     * @return string|null
     * @see https://developer.mozilla.org/en-US/docs/Web/CSS/Attribute_selectors#syntax for references
     */
    public function getOperator(): ?string
    {
        return $this->operator ?: null;
    }

    /**
     * The actual value of the attribute
     *
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * The suffix of the operator
     *
     * Can be either "i" or "s"
     *
     * @return string|null
     * @see https://developer.mozilla.org/en-US/docs/Web/CSS/Attribute_selectors#attr_operator_value_i for reference
     */
    public function getSuffix(): ?string
    {
        return $this->suffix ?: null;
    }

    /**
     * Determine whether the attribute is flagged as case sensitive
     *
     * @return boolean
     */
    public function isCaseInSensitive(): bool
    {
        return strtolower($this->suffix ?? '') === 'i';
    }

    /**
     * Parse attribute string and populate this object
     *
     * @param string $attribute
     * @return void
     */
    private function preprocessAttributes(string $attribute): void
    {
        // split the attribute (quoted value are in base64 encoding)
        $segment = explode('=', $attribute, 2);

        [$this->name, $this->operator] = $this->splitKey($segment[0]);

        // split the value segment
        $valueSegment = array_map('trim', explode(' ', $segment[1] ?? ''));

        if (count($valueSegment) === 1) {
            $valueSegment[] = null;
        }

        [$value, $this->suffix] = $valueSegment;

        // if value is in quote, decode it from base64
        $value = (new Transformer())->decodeQuotedStrings($value);

        // if value is empty (and not zero), express as null
        // else, remove the surrounding quotes
        $this->value = in_array($value, ['', null], true) ? null : trim($value, "'\"");
    }

    /**
     * Separate the attribute name from the operator
     *
     * @param string $key The attribute name + operator
     * @return array<string|null>
     * @see https://developer.mozilla.org/en-US/docs/Web/CSS/Attribute_selectors#syntax for attribute operator reference
     */
    public function splitKey(string $key): array
    {
        preg_match('/^([\w\-]+)([~^$*|]?)$/', $key, $matches);

        return [$matches[1] ?: $key, $matches[2] ?: null];
    }
}
