<?php

namespace Ucscode\PHPDocument\Parser\Engine;

use Ucscode\PHPDocument\Contracts\ElementInterface;
use Ucscode\PHPDocument\Contracts\NodeInterface;

class Matcher
{
    /**
     * @var array<int, boolean>
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

    public function matchesNode(): bool
    {
        return $this->node instanceof ElementInterface && !in_array(false, $this->matches, true);
    }

    protected function validateNodeAgainstTokenizer(): void
    {
        if (!$this->node instanceof ElementInterface) {
            return;
        }

        if ($tag = $this->tokenizer->getTag()) {
            $this->matches['tag'] = $this->node->getNodeName() === strtoupper($tag);
        }

        if ($id = $this->tokenizer->getId()) {
            $this->matches['id'] = $this->node->getAttribute('id') === $id;
        }

        if (!empty($this->tokenizer->getClasses())) {
            // ensure all class in the tokenizer also exist on the node
            $this->matches['classes'] = empty(array_diff(
                $this->tokenizer->getClasses(),
                $this->node->classList->toArray()
            ));
        }

        if (!empty($this->tokenizer->getAttributes())) {
            /**
             * This is more complex due to attribute operators
             * @see https://developer.mozilla.org/en-US/docs/Web/CSS/Attribute_selectors
             */
            $attributeMatcher = new AttributeMatcher($this->node, $this->tokenizer->getAttributes(true));
            $this->matches['attributes'] = $attributeMatcher->matchesNode();
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
