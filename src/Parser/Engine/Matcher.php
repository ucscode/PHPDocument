<?php

namespace Ucscode\UssElement\Parser\Engine;

use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Contracts\NodeInterface;

/**
 * This class compares a CSS selector with a node to verify that the node's attributes match the selector's criteria
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class Matcher
{
    protected ?AttributeMatcher $attributeMatcher = null;

    /**
     * @var array<string, null|bool>
     */
    protected array $matches = [
        'tag' => null,
        'id' => null,
        'class' => null,
        'attributes' => null,
        'pseudo-class' => null,
        'pseudo-elements' => null,
        'pseudo-functions' => null,
    ];

    /**
     * @param ElementInterface|null $node
     * @param Tokenizer $tokenizer Selector attributes should have encoded values
     */
    public function __construct(protected ?NodeInterface $node, protected Tokenizer $tokenizer)
    {
        $this->validateNodeAgainstTokenizer();
    }

    /**
     * @return array<string, bool>
     */
    public function getMatches(): array
    {
        return $this->matches;
    }

    public function getAttributeMatcher(): ?AttributeMatcher
    {
        return $this->attributeMatcher;
    }

    public function matchesNode(): bool
    {
        return $this->node instanceof ElementInterface &&
            !in_array(false, $this->matches, true) &&
            in_array(true, $this->matches, true)
        ;
    }

    protected function validateNodeAgainstTokenizer(): void
    {
        if (!$this->node instanceof ElementInterface) {
            return;
        }

        if ($tag = $this->tokenizer->getTag()) {
            $this->matches['tag'] = $this->node->getNodeName() === strtoupper($tag) || $tag === '*';
        }

        if ($id = $this->tokenizer->getId()) {
            $this->matches['id'] = $this->node->getAttribute('id') === $id;
        }

        if (!empty($this->tokenizer->getClasses())) {
            // ensure all class in the tokenizer also exist on the node
            $classDifference = array_diff($this->tokenizer->getClasses(), $this->node->getClassList()->toArray());

            $this->matches['classes'] = empty($classDifference);
        }

        if (!empty($this->tokenizer->getAttributes())) {
            /**
             * This is more complex due to attribute operators
             * @see https://developer.mozilla.org/en-US/docs/Web/CSS/Attribute_selectors
             */
            $this->attributeMatcher = new AttributeMatcher($this->node, $this->tokenizer->getAttributeDtoCollection());

            $this->matches['attributes'] = $this->attributeMatcher->matchesNode();
        }

        if (!empty($this->tokenizer->getPseudoClasses())) {
            //
        }

        if (!empty($this->tokenizer->getPseudoFunctions())) {
            //
        }

        if (!empty($this->tokenizer->getPseudoElements())) {
            //
        }
    }
}
