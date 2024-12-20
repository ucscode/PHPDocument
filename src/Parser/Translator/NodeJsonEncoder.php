<?php

namespace Ucscode\UssElement\Parser\Translator;

use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Enums\NodeTypeEnum;
use Ucscode\UssElement\Support\AbstractCharacterData;

class NodeJsonEncoder implements \Stringable
{
    public function __construct(protected NodeInterface $node)
    {

    }

    public function __toString(): string
    {
        return $this->encode();
    }

    /**
     * Convert node instance to array
     *
     * @return array
     */
    public function normalize(): array
    {
        return $this->createElementArray($this->node, null);
    }

    /**
     * Serialize node as json
     *
     * @return string
     */
    public function encode(bool $prettyPrint = false): string
    {
        $flags = JSON_HEX_TAG | JSON_THROW_ON_ERROR;

        if ($prettyPrint) {
            $flags |= JSON_PRETTY_PRINT;
        }

        return json_encode($this->normalize(), $flags);
    }

    /**
     * Recursive node normalizer
     *
     * @param NodeInterface $node
     * @param NodeInterface|null $parent
     * @return array
     */
    private function createElementArray(NodeInterface $node, ?NodeInterface $parent): array
    {
        return [
            'nodeType' => $node->getNodeType(),
            'nodeName' => $node->getNodeName(),
            'attributes' => $node instanceof ElementInterface ? $node->getAttributes()->toArray() : null,
            'void' => $node instanceof ElementInterface ? $node->isVoid() : null,
            'visible' => $node->isVisible(),
            'meta' => match($node->getNodeType()) {
                NodeTypeEnum::NODE_TEXT->value => $this->getCharacterDataMeta($node),
                NodeTypeEnum::NODE_COMMENT->value => $this->getCharacterDataMeta($node),
                default => [],
            },
            'childNodes' => array_map(
                fn (NodeInterface $childNode) => $this->createElementArray($childNode, $node),
                $node->getChildNodes()->toArray()
            ),
        ];
    }

    /**
     * CharacterData meta data
     *
     * @param AbstractCharacterData $node
     * @return array
     */
    private function getCharacterDataMeta(AbstractCharacterData $node): array
    {
        return [
            'data' => $node->getData(),
        ];
    }
}
