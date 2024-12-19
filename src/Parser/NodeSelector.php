<?php

namespace Ucscode\PHPDocument\Parser;

use Ucscode\PHPDocument\Collection\HtmlCollection;
use Ucscode\PHPDocument\Contracts\ElementInterface;
use Ucscode\PHPDocument\Contracts\NodeInterface;
use Ucscode\PHPDocument\Parser\Engine\Matcher;
use Ucscode\PHPDocument\Parser\Engine\Tokenizer;
use Ucscode\PHPDocument\Parser\Engine\Transformer;

/**
 * Selector Abstract Syntax Tree
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_selectors#terms
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/Attribute_selectors
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class NodeSelector
{
    // Abstract Syntax Tree
    protected array $selectorAST;
    protected Transformer $transformer;
    protected array $elementList = [];

    public function __construct(protected ElementInterface|NodeInterface $node, protected string $selector)
    {
        if ($this->node instanceof ElementInterface) {
            $this->transformer = new Transformer();
            $this->parseNodeRecursively();
        }
    }

    public function getResult(): HtmlCollection
    {
        return new HtmlCollection($this->elementList);
    }

    /**
     * Find the elements that matches the last selector in the chunk
     *
     * @return void
     */
    protected function parseNodeRecursively(): void
    {
        $encodedSelector = $this->transformer->encodeAttributes(
            $this->transformer->encodeQuotedStrings($this->selector)
        );

        $selectorChunks = array_map(
            fn (string $selector) => $this->transformer->splitIndividualSelector($selector),
            $this->transformer->splitGroupedSelectors($encodedSelector)
        );

        foreach ($selectorChunks as $hierarchicalSelector) {
            $this->branchElementTraversal($this->node->getChildren(), $hierarchicalSelector);
        };
    }

    /**
     * Recursively find every child that matches the last selector in the sequence
     *
     * @param HtmlCollection $children  Collection of children to test for matches
     * @param string[] $selectors       Chunk of selectors
     */
    protected function branchElementTraversal(HtmlCollection $children, array $selectors): void
    {
        /**
         * @var ElementInterface $node
         */
        foreach ($children as $node) {
            if ($node->getChildren()->count()) {
                $this->branchElementTraversal($node->getChildren(), $selectors);
            }

            /**
             * Using the last selector in the chunk, create a tokenizer
             * And test if the descendant node matches the selector
             */
            $tokenizer = new Tokenizer(end($selectors));
            $matcher = new Matcher($node, $tokenizer);

            if ($matcher->matchesNode()) {
                // Found! Now traverse parent nodes
                $this->parentElementTraversal($node, array_slice($selectors, 0, -1), $node);
            }
        }
    }

    /**
     * Recursively iterate the node parent to verify it matches the selectors sequence
     *
     * Once the selectors is empty, the target node becomes accepted
     *
     * @param ?ElementInterface $node   The node whose parent needs to be matched
     * @param array $selectors          The selector sequence for matching parent nodes.
     * @param ElementInterface $target  The base node that started the recursion
     */
    protected function parentElementTraversal(?ElementInterface $node, array $selectors, ElementInterface $target): void
    {
        if (empty($selectors)) {
            // Algorithm passed! Target node accepted
            if (!in_array($target, $this->elementList)) {
                $this->elementList[] = $target;
            }

            return;
        }

        /*
         * If [the selectors is not empty and] the parent node is empty or the current node is
         * same as the [root] node that is being queried, the algorithm has failed!
         */
        if (!$node->getParentElement() || $node === $this->node) {
            return;
        }

        /*
         * Instantiate the tokenizer using the last selector in the sequence
         * And instantiate the matcher using the tokenizer
         */
        $tokenizer = new Tokenizer(end($selectors));
        $parentMatcher = new Matcher($node->getParentElement(), $tokenizer);

        if (!$parentMatcher->matchesNode()) {
            // Keep traversing until a match is found
            $this->parentElementTraversal($node->getParentElement(), $selectors, $target);

            return;
        }

        /**
         * Remove the last value in the selector sequence and continue searching for parent
         * nodes that matches the remaining values in the sequence
         */
        $this->parentElementTraversal($node->getParentElement(), array_slice($selectors, 0, -1), $target);
    }
}
