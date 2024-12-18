<?php

namespace Ucscode\PHPDocument\Test\Parser\Codec;

use PHPUnit\Framework\TestCase;
use Ucscode\PHPDocument\Parser\Codec\Transformer;

class TransformerTest extends TestCase
{
    public const CSS_SELECTOR = 'body#id.body .container [data-name*="This is [not] a .class but has :signs like \" which are still [strings of [the #attributes even if it\'s a quote \"ve\'r"]~ .form-fieldset:disabled > [data-vue] +a[href$=\\\'.org\' i], .name>[data-mode=\"slip, or rise\"] > div';

    public function testTransformerEncodingAndDecoding(): void
    {
        $transformer = new Transformer();

        $encodedAttrValues = $transformer->encodeQuotedStrings(self::CSS_SELECTOR);
        $encodedAttrs = $transformer->encodeSelectorAttributes($encodedAttrValues);
        $reverseAttrs = $transformer->decodeSelectorAttributes($encodedAttrs);
        $reversedAttrValues = $transformer->decodeQuotedStrings($reverseAttrs);

        $this->assertSame($reverseAttrs, $encodedAttrValues);
        $this->assertSame($reversedAttrValues, self::CSS_SELECTOR);
    }

    public function testTransformerSplitings(): void
    {
        $transformer = new Transformer();

        $encodeding = $transformer->encodeSelectorAttributes(
            $transformer->encodeQuotedStrings(self::CSS_SELECTOR)
        );

        $ruleset = $transformer->splitGroupedSelectors($encodeding);

        $this->assertCount(2, $ruleset);

        $selectorChunks1 = $transformer->splitIndividualSelector($ruleset[0]);
        $selectorChunks2 = $transformer->splitIndividualSelector($ruleset[1]);

        $this->assertCount(9, $selectorChunks1);
        $this->assertCount(5, $selectorChunks2);

        $selectorChunks = [
            $transformer->decodeAttributesInSelectorChunks($selectorChunks1, true),
            $transformer->decodeAttributesInSelectorChunks($selectorChunks2, true),
        ];

        foreach ($selectorChunks as $chunks) {
            foreach ($chunks as $selector) {
                $this->assertStringContainsString($selector, self::CSS_SELECTOR);
            }
        }
    }
}
