<?php

namespace Ucscode\UssElement\Serializer;

use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Enums\NodeTypeEnum;
use Ucscode\UssElement\Node\CommentNode;
use Ucscode\UssElement\Node\DocumentTypeNode;
use Ucscode\UssElement\Node\ElementNode;
use Ucscode\UssElement\Node\TextNode;

/**
 * A parser that converts JSON string into `NodeInterface` instance
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class NodeJsonDecoder
{
    public function __construct(protected string $json)
    {

    }

    /**
     * Return an array of the decoded node json
     *
     * @return array<string, mixed>
     */
    public function normalize(): array
    {
        return json_decode($this->json, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Decodes the node json into a node instance
     *
     * @return NodeInterface
     */
    public function decode(): NodeInterface
    {
        return $this->createNodeIntefaceInstance($this->normalize(), null);
    }

    /**
     * Recursive node instance creator
     *
     * @param array<string, mixed> $data
     * @param NodeInterface|null $parent
     * @return NodeInterface
     */
    private function createNodeIntefaceInstance(array $data, ?NodeInterface $parent): NodeInterface
    {
        $node = match($data['nodeType']) {
            NodeTypeEnum::NODE_COMMENT->value => $this->createCommentNode($data),
            NodeTypeEnum::NODE_TEXT->value => $this->createTextNode($data),
            NodeTypeEnum::NODE_DOCUMENT_TYPE->value => $this->createDocumentTypeNode($data),
            default => $this->createElementNode($data)
        };

        if (!empty($data['childNodes'])) {
            foreach ($data['childNodes'] as $childData) {
                $node->appendChild($this->createNodeIntefaceInstance($childData, $node));
            }
        }

        return $node;
    }

    /**
     * Factory and configuration method for comment nodes
     *
     * @param array<string, mixed> $data
     * @return CommentNode
     */
    private function createCommentNode(array $data): CommentNode
    {
        $node = new CommentNode($data['meta']['data']);

        return $this->configureNode($node, $data);
    }

    /**
     * Factory and configuration method for text nodes
     *
     * @param array<string, mixed> $data
     * @return TextNode
     */
    private function createTextNode(array $data): TextNode
    {
        $node = new TextNode($data['meta']['data']);

        return $this->configureNode($node, $data);
    }

    /**
     * Factory and configuration method for document type nodes
     *
     * @param array<string, mixed> $data
     * @return DocumentTypeNode
     */
    private function createDocumentTypeNode(array $data): DocumentTypeNode
    {
        $node = new DocumentTypeNode($data['nodeName']);

        return $this->configureNode($node, $data);
    }

    /**
     * Factory and configuration method for element nodes
     *
     * @param array<string, mixed> $data
     * @return ElementNode
     */
    private function createElementNode(array $data): ElementNode
    {
        $node = new ElementNode($data['nodeName'], $data['attributes']);

        $node->setVoid($data['void']);

        return $this->configureNode($node, $data);
    }

    /**
     * General purpose configuration method for all node types
     *
     * @template T of NodeInterface
     * @param T $node
     * @param array<string, mixed> $data
     * @return T
     */
    private function configureNode(NodeInterface $node, array $data): NodeInterface
    {
        return $node
            ->setVisible($data['visible'])
        ;
    }
}
