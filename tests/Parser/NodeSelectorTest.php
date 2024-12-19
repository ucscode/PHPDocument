<?php

namespace Ucscode\PHPDocument\Test\Parser;

use PHPUnit\Framework\TestCase;
use Ucscode\PHPDocument\Parser\NodeSelector;
use Ucscode\PHPDocument\Test\Traits\NodeHelperTrait;

class NodeSelectorTest extends TestCase
{
    use NodeHelperTrait;

    public function testNodeSelectorCases(): void
    {
        $collection = (new NodeSelector(
            $this->getNodeBody(),
            '
            body .case-1, 
            div .case-1, 
            div form .btn
        '
        ))->getResult();

        $this->assertCount(4, $collection);

        $collection = (new NodeSelector(
            $this->getNodeDiv(),
            '
            form.btn, 
            div form .btn, 
            form .btn-primary, 
            [action][name="form"] [name^=user][type$=text][value="224"]
        '
        ))->getResult();

        $this->assertCount(2, $collection);

        $collection = (new NodeSelector($this->getNodeBody(), '*'))->getResult();

        $this->assertCount(8, $collection);
    }
}
