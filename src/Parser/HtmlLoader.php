<?php

namespace Ucscode\UssElement\Parser;

use DOMAttr;
use DOMComment;
use DOMDocument;
use DOMDocumentType;
use DOMElement;
use DOMNode;
use DOMText;
use Ucscode\UssElement\Collection\NodeList;
use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Enums\NodeTypeEnum;
use Ucscode\UssElement\Node\CommentNode;
use Ucscode\UssElement\Node\DocumentTypeNode;
use Ucscode\UssElement\Node\ElementNode;
use Ucscode\UssElement\Node\TextNode;

/**
 * A parser that converts HTML string into NodeInterface
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class HtmlLoader
{
    /**
     * @var NodeList<int, NodeInterface>
     */
    protected NodeList $nodeList;

    /**
     * @param string $html5
     * @param boolean $preserveWhiteSpaces
     */
    public function __construct(protected string $html5, protected bool $preserveWhiteSpaces = false)
    {
        $document = new DOMDocument();

        // Suppress HTML5 warnings
        libxml_use_internal_errors(true);

        $document->loadHTML($html5, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);

        $this->parseDocumentNodes($document);
    }

    /**
     * @return NodeList<int, NodeInterface>
     */
    public function getNodeList(): NodeList
    {
        return $this->nodeList;
    }

    /**
     * @param DOMNode $domNode
     */
    protected function parseDocumentNodes(DOMNode $domNode): void
    {
        $collection = [];

        foreach ($domNode->childNodes as $childNode) {
            if ($node = $this->createAppropriateNode($childNode)) {
                $collection[] = $node;
            };
        }

        // @phpstan-ignore-next-line
        $this->nodeList = new NodeList($collection);
    }

    /**
     * Load the NodeInterface that matches the give DOMNode
     *
     * @param DOMNode $domNode
     * @return NodeInterface|null
     */
    protected function createAppropriateNode(DOMNode $domNode): ?NodeInterface
    {
        if (!in_array($domNode->nodeType, [3, 10, 8])) {
            $debugList = ['div', 'button', 'p', 'h5'];

            if (!in_array(strtolower($domNode->nodeName), $debugList)) {
                // var_dump($domNode);
            };
        };

        return match($domNode->nodeType) {
            NodeTypeEnum::NODE_COMMENT->value => $this->createCommentNode($domNode),
            NodeTypeEnum::NODE_DOCUMENT_TYPE->value => $this->createDocumentTypeNode($domNode),
            NodeTypeEnum::NODE_TEXT->value => $this->createTextNode($domNode),
            default => $this->createElementNode($domNode),
        };
    }

    /**
     * Create an html doctype from the dom document type
     *
     * @param DOMDocumentType $domDoctype
     * @return DocumentTypeNode
     */
    protected function createDocumentTypeNode(DOMDocumentType $domDoctype): DocumentTypeNode
    {
        return new DocumentTypeNode($domDoctype->name);
    }

    /**
     * Create commend node from the dom comment
     *
     * @param DOMComment $domComment
     * @return CommentNode
     */
    protected function createCommentNode(DOMComment $domComment): CommentNode
    {
        return new CommentNode($domComment->data);
    }

    /**
     * Create an element node from dom element
     *
     * @param DOMElement $domElement
     * @return ElementNode
     */
    protected function createElementNode(DOMElement $domElement): ElementNode
    {
        $node = new ElementNode($domElement->nodeName);

        if ($domElement->hasAttributes()) {
            /**
             * @var DOMAttr $attribute
             */
            foreach ($domElement->attributes as $attribute) {
                $node->setAttribute($attribute->name, $attribute->value);
            }
        }

        if ($domElement->hasChildNodes()) {
            foreach ($domElement->childNodes as $domChildElement) {
                if ($childNode = $this->createAppropriateNode($domChildElement)) {
                    $node->appendChild($childNode);
                }
            }
        }

        return $node;
    }

    /**
     * Create a text node from dom text
     *
     * @param DOMText $domText
     * @return TextNode
     */
    protected function createTextNode(DOMText $domText): ?TextNode
    {
        $node = new TextNode($domText->data);

        if (!$this->preserveWhiteSpaces && $node->isContentWhiteSpace()) {
            return null;
        }

        return $node;
    }
}
