<?php

namespace Ucscode\PHPDocument\Parser\Engine;

use Ucscode\PHPDocument\Parser\Enum\NodeQueryRegexpEnum;

class Transformer
{
    /**
     * Encode attribute values to base64
     *
     * This is done to improve focus on css related rules
     *
     * @param string $selector
     * @return string
     */
    public function encodeQuotedStrings(string $selector): string
    {
        return preg_replace_callback(NodeQueryRegexpEnum::EXPR_QUOTED_STRING->value, function ($match) {
            return sprintf('%1$s%2$s%1$s', $match[1], base64_encode($match[2]));
        }, $selector);
    }

    /**
     * Reverse attribute value base64 encoding
     *
     * @param string $encodedValueSelector
     * @return string
     */
    public function decodeQuotedStrings(string $encodedValueSelector): string
    {
        return preg_replace_callback(NodeQueryRegexpEnum::EXPR_QUOTED_STRING->value, function ($match) {
            return sprintf('%1$s%2$s%1$s', $match[1], base64_decode($match[2]));
        }, $encodedValueSelector);
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
    public function encodeSelectorAttributes(string $selector): string
    {
        return preg_replace_callback(NodeQueryRegexpEnum::EXPR_ATTRIBUTE->value, function ($match) {
            return sprintf('[%s]', base64_encode($match[1]));
        }, $selector);
    }

    /**
     * Reverse attribute base64 encoding
     *
     * @param string $encodedSelector
     * @return string
     */
    public function decodeSelectorAttributes(string $encodedSelector): string
    {
        return preg_replace_callback(NodeQueryRegexpEnum::EXPR_ATTRIBUTE->value, function ($match) {
            return sprintf('[%s]', base64_decode($match[1], true));
        }, $encodedSelector);
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

        return array_filter(array_map('trim', explode(' ', $selector)), fn (string $value) => $value !== '');
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
            if (preg_match(NodeQueryRegexpEnum::EXPR_ATTRIBUTE->value, $selector)) {
                $transcoder = new Transformer();
                $selector = $transcoder->decodeSelectorAttributes($selector);

                if ($decodeQuotedStrings) {
                    $selector = $transcoder->decodeQuotedStrings($selector);
                }
            }

            return $selector;
        }, $selectorChunks);
    }
}
