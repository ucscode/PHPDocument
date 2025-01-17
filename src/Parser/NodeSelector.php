<?php

namespace Ucscode\UssElement\Parser;

use Ucscode\UssElement\Collection\ElementList;
use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Parser\Engine\Matcher;
use Ucscode\UssElement\Parser\Engine\Tokenizer;
use Ucscode\UssElement\Parser\Engine\Transformer;

/**
 * A parser that matches css rules against an element
 *
 * This parser is utilized by the `ElementInterface::querySelectorAll()` method
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_selectors#terms
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/Attribute_selectors
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class NodeSelector
{
    protected Transformer $transformer;
    /**
     * @var array<int, ElementInterface>
     */
    protected array $elementList = [];

    /**
     * @param ElementInterface|NodeInterface $node
     * @param string $selector
     */
    public function __construct(protected NodeInterface $node, protected string $selector)
    {
        if ($this->node instanceof ElementInterface) {
            $this->transformer = new Transformer();
            $this->parseNodeRecursively();
        }
    }

    /**
     * @return ElementList<int, ElementInterface>
     */
    public function getResult(): ElementList
    {
        return new ElementList($this->elementList);
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
            // @phpstan-ignore-next-line
            $this->branchElementTraversal($this->node->getChildren(), $hierarchicalSelector);
        };
    }

    /**
     * Recursively find every child that matches the last selector in the sequence
     *
     * @param ElementList<int, ElementInterface> $children  Collection of children to test for matches
     * @param string[] $selectors       Chunk of selectors
     */
    protected function branchElementTraversal(ElementList $children, array $selectors): void
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
     * @param array<string> $selectors  The selector sequence for matching parent nodes.
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
