<?php

namespace Ucscode\UssElement\Test\Parser\Engine;

use PHPUnit\Framework\TestCase;
use Ucscode\UssElement\Parser\Engine\Matcher;
use Ucscode\UssElement\Parser\Engine\Tokenizer;
use Ucscode\UssElement\Test\Traits\NodeHelperTrait;
use Ucscode\UssElement\Contracts\ElementInterface;

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
                $this->getNodeInput(),
                true,
            ],
            '[name=\'username\'][value=][type=""]' => [
                $this->getNodeInput(),
                false,
            ],
            '[name][value="224"][type=]' => [
                $this->getNodeInput(),
                true,
            ],
            '[name][value="224"][type=text]' => [
                $this->getNodeInput(),
                true,
            ],
            '[name][value="224"][type=tex]' => [
                $this->getNodeInput(),
                false,
            ],
            '[href$=.com][error=3]' => [
                $this->getNodeA(),
                true,
            ],
            '[href^=https][error=3]' => [
                $this->getNodeA(),
                true,
            ],
            '[src=300]#factor.img-fluid' => [
                $this->getNodeImg(),
                false
            ],
            '[src*=300]#factor.img-fluid' => [
                $this->getNodeImg(),
                true
            ],
            '[src$=/FFF]#factor.img-fluid' => [
                $this->getNodeImg(),
                false
            ],
            '[src$=/FFF i]#factor.img-fluid' => [
                $this->getNodeImg(),
                true
            ],
            'img[src$="/FFF" i].img-fluid#factor' => [
                $this->getNodeImg(),
                true
            ],
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
                'Failure at index %s that %s %s %s',
                $index,
                $selector,
                $context[1] ? 'matches' : 'does not match',
                $context[0]->getOpenTag(),
            );

            $this->assertSame($context[1], $matcher->matchesNode(), $message);

            $index++;
        }
    }
}
