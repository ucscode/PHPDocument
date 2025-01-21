<?php

namespace Ucscode\UssElement\Test\Parser\Translator;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Enums\NodeNameEnum;
use Ucscode\UssElement\Serializer\NodeJsonDecoder;
use Ucscode\UssElement\Serializer\NodeJsonEncoder;
use Ucscode\UssElement\Test\Traits\NodeHelperTrait;

class NodeJsonEncoderTest extends TestCase
{
    use NodeHelperTrait;

    public function testElementEncoding(): string
    {
        $encoder = new NodeJsonEncoder($this->getBodyNode());
        $normalize = $encoder->normalize();

        $this->assertSame(NodeNameEnum::NODE_BODY->value, $normalize['nodeName']);
        $this->assertSame(NodeNameEnum::NODE_BODY->value, $normalize['nodeName']);
        $this->assertNotNull($normalize['childNodes'][0]['attributes']);
        $this->assertSame('dark', $normalize['childNodes'][0]['attributes']['data-theme'] ?? null);
        $this->assertSame($normalize['nodeId'], $normalize['childNodes'][0]['parentId']);

        return $encoder->encode();
    }

    #[Depends('testElementEncoding')]
    public function testJsonDecoding(string $nodeJson): void
    {
        $decoder = new NodeJsonDecoder($nodeJson);
        $normalize = $decoder->normalize();

        $this->assertSame(NodeNameEnum::NODE_BODY->value, $normalize['nodeName']);
        $this->assertSame(NodeNameEnum::NODE_BODY->value, $normalize['nodeName']);
        $this->assertNotNull($normalize['childNodes'][0]['attributes']);
        $this->assertSame('dark', $normalize['childNodes'][0]['attributes']['data-theme'] ?? null);

        /**
         * @var ElementInterface $element
         */
        $element = $decoder->decode();

        $this->assertInstanceOf(ElementInterface::class, $element);
        $this->assertSame('BODY', $element->getNodeName());
        $this->assertNotNull($element->getChildren()->first());
        $this->assertSame('position-relative case-1', $element->getChildren()->first()->getAttribute('class'));
        $this->assertSame($this->getBodyNode()->render(), $element->render());
        $this->assertSame($this->jsonWithoutId($nodeJson), $this->jsonWithoutId($this->getBodyNode()->toJson()));
    }

    private function jsonWithoutId(string $json): string
    {
        return preg_replace('/"(?:nodeId|parentId)":\d+,/', '', $json);
    }
}
