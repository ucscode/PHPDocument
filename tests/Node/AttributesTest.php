<?php

namespace Ucscode\UssElement\Test\Node;

use PHPUnit\Framework\TestCase;
use Ucscode\UssElement\Collection\Attributes;
use Ucscode\UssElement\Support\ObjectReflector;

final class AttributesTest extends TestCase
{
    public function testAttributeMethods(): void
    {
        $attributes = new Attributes([
            'data-name' => 'ucscode',
        ]);

        $attributeReflector = new ObjectReflector($attributes);

        $attributeReflector->invokeMethod('set', 'id', 'local-id');
        $attributeReflector->invokeMethod('set', 'class', 'btn blob-success');

        $this->assertSame($attributes->get('data-name'), 'ucscode');
        $this->assertTrue($attributes->has('id'));

        $attributeReflector->invokeMethod('remove', 'data-name');

        $this->assertFalse($attributes->has('data-name'));
        $this->assertSame($attributes->getNames(), ['id', 'class']);
    }
}
