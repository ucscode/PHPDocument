<?php

namespace Ucscode\UssElement\Serializer;

use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Enums\NodeTypeEnum;
use Ucscode\UssElement\Abstraction\AbstractCharacterData;

/**
 * A parser that converts `NodeInteface` instance into JSON string
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
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
     * @return array<string, mixed>
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
     * @return array<string, mixed>
     */
    private function createElementArray(NodeInterface $node, ?NodeInterface $parent): array
    {
        return [
            'nodeId' => $node->getNodeId(),
            'nodeType' => $node->getNodeType(),
            'nodeName' => $node->getNodeName(),
            'parentId' => $parent?->getNodeId(),
            'attributes' => $this->normalizeAttributes($node),
            'void' => $node instanceof ElementInterface ? $node->isVoid() : null,
            'visible' => $node->isVisible(),
            'meta' => match($node->getNodeType()) {
                // @phpstan-ignore-next-line
                NodeTypeEnum::NODE_TEXT->value => $this->getCharacterDataMeta($node),
                // @phpstan-ignore-next-line
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
     * @return array<string, mixed>
     */
    private function getCharacterDataMeta(AbstractCharacterData $node): array
    {
        return [
            'data' => $node->getData(),
        ];
    }

    /**
     * Normalize element attributes to array
     *
     * @param NodeInterface $node
     * @return null|array<string, mixed>
     */
    private function normalizeAttributes(NodeInterface $node): ?array
    {
        if (!$node instanceof ElementInterface) {
            return null;
        }

        return array_map(fn (string $value) => $value, $node->getAttributes()->toArray());
    }
}
