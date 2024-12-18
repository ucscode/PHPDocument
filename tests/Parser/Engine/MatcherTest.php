<?php

namespace Ucscode\PHPDocument\Test\Parser\Engine;

use PHPUnit\Framework\TestCase;
use Ucscode\PHPDocument\Enums\NodeNameEnum;
use Ucscode\PHPDocument\Node\ElementNode;
use Ucscode\PHPDocument\Parser\Engine\Matcher;
use Ucscode\PHPDocument\Parser\Engine\Tokenizer;
use Ucscode\PHPDocument\Parser\Engine\Transformer;
use Ucscode\PHPDocument\Test\Traits\NodeHelperTrait;

class MatcherTest extends TestCase
{
    use NodeHelperTrait;

    public function testElementMatch(): void
    {
        $matcher = new Matcher(
            $this->getNodeDiv(),
            new Tokenizer((new Transformer())->encodeQuotedStrings('#position-relative[data-theme*="dark"]'))
        );

        $this->assertTrue($matcher->matchesNode());

        $matcher = new Matcher(
            $this->getNodeInput(),
            new Tokenizer((new Transformer())->encodeQuotedStrings('[name=\'username\'][value=][type=""]'))
        );

        // $this->assertFalse($matcher->matchesNode());
    }
}
