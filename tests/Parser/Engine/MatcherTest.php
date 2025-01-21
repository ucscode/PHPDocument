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
                $this->getDivNode(),
                true,
            ],
            '[name=\'username\'][value=][type="text"]' => [
                $this->getInputNode(),
                true,
            ],
            '[name=\'username\'][value=][type=""]' => [
                $this->getInputNode(),
                false,
            ],
            '[name][value="224"][type=]' => [
                $this->getInputNode(),
                true,
            ],
            '[name][value="224"][type=text]' => [
                $this->getInputNode(),
                true,
            ],
            '[name][value="224"][type=tex]' => [
                $this->getInputNode(),
                false,
            ],
            '[href$=.com][error=3]' => [
                $this->getANode(),
                true,
            ],
            '[href^=https][error=3]' => [
                $this->getANode(),
                true,
            ],
            '[src=300]#factor.img-fluid' => [
                $this->getImgNode(),
                false
            ],
            '[src*=300]#factor.img-fluid' => [
                $this->getImgNode(),
                true
            ],
            '[src$=/FFF]#factor.img-fluid' => [
                $this->getImgNode(),
                false
            ],
            '[src$=/FFF i]#factor.img-fluid' => [
                $this->getImgNode(),
                true
            ],
            'img[src$="/FFF" i].img-fluid#factor' => [
                $this->getImgNode(),
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
