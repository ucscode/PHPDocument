<?php

namespace Ucscode\UssElement\Test\Collection;

use PHPUnit\Framework\TestCase;
use Ucscode\UssElement\Collection\ClassList;

class ClassListTest extends TestCase
{
    public function testAdd()
    {
        $classList = new ClassList();
        $classList->add('btn btn-primary');
        $this->assertCount(2, $classList->toArray());
        $this->assertContains('btn-primary', $classList->toArray());
        $this->assertContains('btn', $classList->toArray());
    }

    public function testRemove()
    {
        $classList = new ClassList();
        $classList->add('btn btn-primary');
        $classList->remove('btn-primary');
        $this->assertCount(1, $classList->toArray());
        $this->assertNotContains('btn-primary', $classList->toArray());
    }

    public function testReplace()
    {
        $classList = new ClassList();
        $classList->add('btn btn-primary');
        $classList->replace('btn-primary', 'btn-success');
        $this->assertCount(2, $classList->toArray());
        $this->assertNotContains('btn-primary', $classList->toArray());
        $this->assertContains('btn-success', $classList->toArray());
    }

    public function testContains()
    {
        $classList = new ClassList();
        $classList->add('btn btn-primary');
        $this->assertTrue($classList->contains('btn-primary'));
        $this->assertFalse($classList->contains('btn-danger'));
    }

    public function testToggle()
    {
        $classList = new ClassList();
        $classList->add('btn');
        $classList->toggle('btn');
        $this->assertNotContains('btn', $classList->toArray());
        $classList->toggle('btn-primary');
        $this->assertContains('btn-primary', $classList->toArray());
    }
}
