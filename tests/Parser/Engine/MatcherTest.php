<?php

namespace Ucscode\PHPDocument\Test\Parser\Engine;

use PHPUnit\Framework\TestCase;
use Ucscode\PHPDocument\Parser\Engine\Matcher;
use Ucscode\PHPDocument\Parser\Engine\Tokenizer;
use Ucscode\PHPDocument\Test\Traits\NodeHelperTrait;
use Ucscode\PHPDocument\Contracts\ElementInterface;

class MatcherTest extends TestCase
{
    use NodeHelperTrait;

    public function dataProvider(): array
    {
        return [
            '.position-relative[data-theme*="dark"]' => [
                $this->getNodeDiv(),
                true,
            ],
            '[name=\'username\'][value=][type="text"]' => [
                $this->getNodeDiv(),
                true,
            ],
            '[name=\'username\'][value=][type=""]' => [
                $this->getNodeDiv(),
                false,
            ],
            '[name][value="224"][type=]' => [
                $this->getNodeDiv(),
                true,
            ],
            '[name][value="224"][type=text]' => [
                $this->getNodeDiv(),
                true,
            ],
            '[name][value="224"][type=tex]' => [
                $this->getNodeDiv(),
                false,
            ]
        ];
    }

    public function testElementMatch(): void
    {
        $index = 0;

        /**
         * @var array{0:ElementInterface,1:boolean} $context
         */
        foreach ($this->dataProvider() as $selector => $context) {
            $matcher = new Matcher(
                $context[0],
                new Tokenizer($this->encodeRawSelector($selector))
            );

            $message = sprintf(
                'Failure at index %s that %s matches %s',
                $index,
                $selector,
                $context[0]->getOpenTag(),
            );

            $this->assertSame($context[1], $matcher->matchesNode(), $message);

            $index++;
        }
    }
}
