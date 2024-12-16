<?php

namespace Ucscode\PHPDocument\Test\Node;

use PHPUnit\Framework\TestCase;
use Ucscode\PHPDocument\Collection\Attributes;

final class AttributesTest extends TestCase
{
    public function testAttributeMethods(): void
    {
        $attributes = new Attributes([
            'data-name' => 'ucscode',
        ]);

        $attributes->set('id', 'local-id');
        $attributes->set('class', 'btn blob-success');

        $this->assertSame($attributes->get('data-name'), 'ucscode');
        $this->assertTrue($attributes->has('id'));

        $attributes->remove('data-name');

        $this->assertFalse($attributes->has('data-name'));
        $this->assertSame($attributes->getNames(), ['id', 'class']);

        $attributes->appendValue('class', 'node');
        $attributes->prependValue('class', 'swag');

        $this->assertStringEndsWith('node', $attributes->get('class'));
        $this->assertStringStartsWith('swag', $attributes->get('class'));

        $this->assertTrue($attributes->hasValue('class', 'btn'));

        $attributes->removeValue('class', 'btn');

        $this->assertStringNotContainsString('btn', $attributes->get('class'));

        $this->assertSame(
            'id="local-id" class="swag blob-success node"',
            $attributes->render(),
        );
    }
}
