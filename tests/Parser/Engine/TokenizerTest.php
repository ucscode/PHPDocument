<?php

namespace Ucscode\UssElement\Test\Parser\Engine;

use PHPUnit\Framework\TestCase;
use Ucscode\UssElement\Parser\Collection\AttributeDtoCollection;
use Ucscode\UssElement\Parser\Dto\AttributeDto;
use Ucscode\UssElement\Parser\Engine\Tokenizer;
use Ucscode\UssElement\Test\Traits\NodeHelperTrait;

class TokenizerTest extends TestCase
{
    use NodeHelperTrait;

    public function testSimpleTokenization(): void
    {
        $tokenizer = new Tokenizer(
            $this->encodeRawSelector('body#water-mark.content-of_the-world[data-value][data-model~="lot" i]')
        );

        $this->assertSame('body', $tokenizer->getTag());
        $this->assertSame('water-mark', $tokenizer->getId());
        $this->assertContains('content-of_the-world', $tokenizer->getClasses());
        $this->assertContains('data-value', $tokenizer->getAttributes());
        $this->assertCount(1, $tokenizer->getClasses());
        $this->assertCount(2, $tokenizer->getAttributes());
    }

    public function testComplexTokenization(): void
    {
        $tokenizer = new Tokenizer(
            $this->encodeRawSelector('div.wrapper.product-collection#main[data-role~="container" i][data-state="active"][name= s][empty=""]:not(.hidden):nth-child(2n+1):enabled::before::after')
        );

        $this->assertSame('div', $tokenizer->getTag());
        $this->assertSame('main', $tokenizer->getId());

        $classes = $tokenizer->getClasses();

        $this->assertCount(2, $classes);
        $this->assertContains('wrapper', $classes);
        $this->assertContains('product-collection', $classes);

        $attributes = $tokenizer->getAttributes();

        $this->assertCount(4, $attributes);
        $this->assertContains('data-role~="container" i', $attributes);
        $this->assertContains('data-state="active"', $attributes);
        $this->assertContains('name= s', $attributes);
        $this->assertContains('empty=""', $attributes);

        $attributeDtoCollection = $tokenizer->getAttributeDtoCollection();

        $this->assertInstanceOf(AttributeDtoCollection::class, $attributeDtoCollection);
        $this->assertCount(4, $attributeDtoCollection);

        $this->assertTrue($attributeDtoCollection->has('data-role'));
        $this->assertTrue($attributeDtoCollection->has('data-state'));
        $this->assertTrue($attributeDtoCollection->has('name'));
        $this->assertTrue($attributeDtoCollection->has('empty'));

        $this->assertContains('active', $attributeDtoCollection->values());
        $this->assertContains('container', $attributeDtoCollection->values());
        $this->assertContains(null, $attributeDtoCollection->values());
        $this->assertContains('', $attributeDtoCollection->values());

        $this->assertSame('container', $attributeDtoCollection->get('data-role')->getValue());
        $this->assertSame('active', $attributeDtoCollection->get('data-state')->getValue());
        $this->assertNull($attributeDtoCollection->get('name')->getValue());
        $this->assertSame('', $attributeDtoCollection->get('empty')->getValue());

        $this->assertCount(2, $tokenizer->getPseudoFunctions());
        $this->assertArrayHasKey('not', $tokenizer->getPseudoFunctions());
        $this->assertContains('.hidden', $tokenizer->getPseudoFunctions());
        $this->arrayHasKey('nth-child', $tokenizer->getPseudoFunctions());
        $this->assertContains('2n+1', $tokenizer->getPseudoFunctions());

        $pseudoFunctions = $tokenizer->getPseudoFunctions();

        $this->assertSame('.hidden', $pseudoFunctions['not']);
        $this->assertSame('2n+1', $pseudoFunctions['nth-child']);

        $this->assertContains('enabled', $tokenizer->getPseudoClasses());

        $this->assertCount(2, $tokenizer->getPseudoElements());
        $this->assertContains('before', $tokenizer->getPseudoElements());
        $this->assertContains('after', $tokenizer->getPseudoElements());
    }
}
