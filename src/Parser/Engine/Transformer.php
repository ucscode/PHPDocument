<?php

namespace Ucscode\UssElement\Parser\Engine;

use Ucscode\UssElement\Parser\Enum\NodeQueryRegexpEnum;

/**
 * A transformer class that encode and decode css selector for several use cases
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class Transformer
{
    /**
     * Encode strings in quotes to base64
     *
     * This is done to avoid "space" character conflict between parent-child selector and attribute value
     *
     * @param string $selector
     * @return string
     */
    public function encodeQuotedStrings(string $selector): string
    {
        return preg_replace_callback(
            NodeQueryRegexpEnum::REGEXP_QUOTED_STRING->value,
            fn (array $match) => sprintf('%1$s%2$s%1$s', $match[1], base64_encode($match[2])),
            $selector
        );
    }

    /**
     * Decode strings in quotes from base64
     *
     * @param string $encodedSelector
     * @return string
     */
    public function decodeQuotedStrings(string $encodedSelector): string
    {
        return preg_replace_callback(
            NodeQueryRegexpEnum::REGEXP_QUOTED_STRING->value,
            fn (array $match) => sprintf('%1$s%2$s%1$s', $match[1], base64_decode($match[2], true)),
            $encodedSelector
        );
    }

    /**
     * Encode attributes to base64
     *
     * This is done to keep reference of attribute operators such as "i" or "s"
     *
     * @param string $selector
     * @return string
     * @see https://developer.mozilla.org/en-US/docs/Web/CSS/Attribute_selectors#attr_operator_value_i
     */
    public function encodeAttributes(string $selector): string
    {
        return preg_replace_callback(
            NodeQueryRegexpEnum::REGEXP_ATTRIBUTES->value,
            fn (array $match) => sprintf('[%s]', base64_encode($match[1])),
            $selector
        );
    }

    /**
     * Reverse attribute base64 encoding
     *
     * @param string $encodedSelector
     * @return string
     */
    public function decodeAttributes(string $encodedSelector): string
    {
        return preg_replace_callback(
            NodeQueryRegexpEnum::REGEXP_ATTRIBUTES->value,
            fn (array $match) => sprintf('[%s]', base64_decode($match[1], true)),
            $encodedSelector
        );
    }

    /**
     * Split multiple selectors by comma
     *
     * @param string $selector selector with encoded attributes
     * @return array<int, string>
     */
    public function splitGroupedSelectors(string $selector): array
    {
        return array_map('trim', explode(",", $selector));
    }

    /**
     * Split an individual selector by spaces (to handle parent-child relationships)
     *
     * @param string $selector selector with encoded attributes
     * @return array<int, string>
     */
    public function splitIndividualSelector(string $selector): array
    {
        // add space around combinators
        $selector = preg_replace('/(\>|\~|\+)/', ' $1 ', $selector);

        return array_filter(
            array_map('trim', explode(' ', $selector)),
            fn (string $value) => $value !== ''
        );
    }

    /**
     * Decode all attributes within selector chunks
     *
     * @param array<int, string> $selectorChunks
     * @return array<int, string> Chunks with decoded attributes
     */
    public function decodeAttributesInSelectorChunks(array $selectorChunks, bool $decodeQuotedStrings = false): array
    {
        return array_map(function (string $selector) use ($decodeQuotedStrings) {
            if (preg_match(NodeQueryRegexpEnum::REGEXP_ATTRIBUTES->value, $selector)) {
                $transcoder = new Transformer();
                $selector = $transcoder->decodeAttributes($selector);

                if ($decodeQuotedStrings) {
                    $selector = $transcoder->decodeQuotedStrings($selector);
                }
            }

            return $selector;
        }, $selectorChunks);
    }
}
