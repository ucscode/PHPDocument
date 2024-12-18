<?php

namespace Ucscode\PHPDocument\Test\Node;

use PHPUnit\Framework\TestCase;
use Ucscode\PHPDocument\Collection\AttributesMutable;

final class AttributesTest extends TestCase
{
    public function testAttributeMethods(): void
    {
        $attributes = new AttributesMutable([
            'data-name' => 'ucscode',
        ]);

        $attributes->set('id', 'local-id');
        $attributes->set('class', 'btn blob-success');

        $this->assertSame($attributes->get('data-name'), 'ucscode');
        $this->assertTrue($attributes->has('id'));

        $attributes->remove('data-name');

        $this->assertFalse($attributes->has('data-name'));
        $this->assertSame($attributes->getNames(), ['id', 'class']);
    }
}
