<?php

namespace Ucscode\UssElement\Test\Parser\Engine;

use PHPUnit\Framework\TestCase;
use Ucscode\UssElement\Test\Traits\NodeHelperTrait;

class TransformerTest extends TestCase
{
    use NodeHelperTrait;

    public const CSS_SELECTOR = 'body#id.body .container [data-name*="This is [not] a .class but has :signs like \" which are still [strings of [the #attributes even if it\'s a quote \"ve\'r"]~ .form-fieldset:disabled > [data-vue] +a[href$=\\\'.org\' i], .name>[data-mode=\"slip, or rise\"] > div';

    public function testTransformerEncodingAndDecoding(): void
    {
        $encodedAttrValues = $this->transformer->encodeQuotedStrings(self::CSS_SELECTOR);
        $encodedAttrs = $this->transformer->encodeAttributes($encodedAttrValues);
        $reverseAttrs = $this->transformer->decodeAttributes($encodedAttrs);
        $reversedAttrValues = $this->transformer->decodeQuotedStrings($reverseAttrs);

        $this->assertSame($reverseAttrs, $encodedAttrValues);
        $this->assertSame($reversedAttrValues, self::CSS_SELECTOR);
    }

    public function testTransformerSplitings(): void
    {
        $encodeding = $this->encodeRawSelector(self::CSS_SELECTOR);
        $ruleset = $this->transformer->splitGroupedSelectors($encodeding);

        $this->assertCount(2, $ruleset);

        $selectorChunks1 = $this->transformer->splitIndividualSelector($ruleset[0]);
        $selectorChunks2 = $this->transformer->splitIndividualSelector($ruleset[1]);

        $this->assertCount(9, $selectorChunks1);
        $this->assertCount(5, $selectorChunks2);

        $selectorChunks = [
            $this->transformer->decodeAttributesInSelectorChunks($selectorChunks1, true),
            $this->transformer->decodeAttributesInSelectorChunks($selectorChunks2, true),
        ];

        foreach ($selectorChunks as $chunks) {
            foreach ($chunks as $selector) {
                $this->assertStringContainsString($selector, self::CSS_SELECTOR);
            }
        }
    }
}
