<?php

namespace Ucscode\PHPDocument\Test\Parser\Engine;

use PHPUnit\Framework\TestCase;
use Ucscode\PHPDocument\Parser\Engine\Tokenizer;

class TokenizerTest extends TestCase
{
    public function testSimpleTokenization(): void
    {
        $tokenizer = new Tokenizer('body#water-mark.content-of_the-world[data-value][data-model~="lot"]');

        $this->assertSame('body', $tokenizer->getTag());
        $this->assertSame('water-mark', $tokenizer->getId());
        $this->assertContains('content-of_the-world', $tokenizer->getClasses());
        $this->assertContains('data-value', $tokenizer->getAttributes());
        $this->assertCount(1, $tokenizer->getClasses());
        $this->assertCount(2, $tokenizer->getAttributes());
    }

    public function testComplexTokenization(): void
    {
        $tokenizer = new Tokenizer('div.wrapper.product-collection#main[data-role~="container"][data-state="active"]:not(.hidden):nth-child(2n+1):enabled::before::after');

        $this->assertSame('div', $tokenizer->getTag());

        $this->assertSame('main', $tokenizer->getId());

        $this->assertCount(2, $tokenizer->getClasses());
        $this->assertContains('wrapper', $tokenizer->getClasses());
        $this->assertContains('product-collection', $tokenizer->getClasses());

        $this->assertCount(2, $tokenizer->getAttributes());
        $this->assertContains('data-role~="container"', $tokenizer->getAttributes());
        $this->assertContains('data-state="active"', $tokenizer->getAttributes());

        $this->assertCount(2, $tokenizer->getAttributes(true));
        $this->assertArrayHasKey('data-role~', $tokenizer->getAttributes(true));
        $this->assertArrayHasKey('data-state', $tokenizer->getAttributes(true));
        $this->assertContains('active', $tokenizer->getAttributes(true));
        $this->assertContains('container', $tokenizer->getAttributes(true));

        $attributes = $tokenizer->getAttributes(true);

        $this->assertSame('container', $attributes['data-role~']);
        $this->assertSame('active', $attributes['data-state']);

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
