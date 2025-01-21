<?php

namespace Ucscode\UssElement\Node;

use Ucscode\UssElement\Collection\Attributes;
use Ucscode\UssElement\Enums\NodeNameEnum;
use Ucscode\UssElement\Collection\ClassList;
use Ucscode\UssElement\Collection\ElementList;
use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Enums\NodeTypeEnum;
use Ucscode\UssElement\Parser\Engine\Matcher;
use Ucscode\UssElement\Parser\Engine\Tokenizer;
use Ucscode\UssElement\Parser\Engine\Transformer;
use Ucscode\UssElement\Parser\NodeSelector;
use Ucscode\UssElement\Parser\HtmlLoader;
use Ucscode\UssElement\Abstraction\AbstractNode;
use Ucscode\UssElement\Support\ObjectReflector;

/**
 * An object oriented representation of HTML element
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class ElementNode extends AbstractNode implements ElementInterface
{
    protected string $tagName;
    protected bool $void;
    protected ClassList $classList;
    protected Attributes $attributes;

    /**
     * @param string|NodeNameEnum $nodeName
     * @param array<string, mixed> $attributes
     */
    public function __construct(string|NodeNameEnum $nodeName, array $attributes = [])
    {
        parent::__construct();

        $this->nodeName = strtoupper($nodeName instanceof NodeNameEnum ? $nodeName->value : $nodeName);
        $this->tagName = $this->nodeName;
        $this->attributes = new Attributes($attributes);
        $this->classList = new ClassList();

        foreach ($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }

        $this->void = in_array(
            $this->nodeName,
            array_map(fn (NodeNameEnum $enum) => $enum->value, NodeNameEnum::voidCases())
        );
    }

    final public function getTagName(): string
    {
        return $this->nodeName;
    }

    public function getNodeTypeEnum(): NodeTypeEnum
    {
        return NodeTypeEnum::NODE_ELEMENT;
    }

    public function getClassList(): ClassList
    {
        return $this->classList;
    }

    public function render(?int $indent = null): string
    {
        $innerHtml = $this->getInnerHtml($indent);

        $openTag = $this->getOpenTag();
        $closeTag = $this->getCloseTag();

        if ($indent !== null) {
            $indentation = max(0, $indent); // set min indentation to "0"
            $htmlIsBlank = trim($innerHtml) === '';

            $openTag = $this->indent($openTag, $indentation, !$htmlIsBlank);
            $closeTag = $this->indent($closeTag, $htmlIsBlank ? 0 : $indentation);
        }

        return sprintf('%s%s%s', $openTag, $innerHtml, $closeTag);
    }

    public function setInnerHtml(string $html): static
    {
        $loadedNodes = (new HtmlLoader($html))->getNodeList()->toArray();

        (new ObjectReflector($this->childNodes))->invokeMethod('replaceItemsProperty', $loadedNodes);

        return $this;
    }

    public function getInnerHtml(?int $indent = null): string
    {
        $render = array_map(
            fn (NodeInterface $node) => !$node->isVisible() ? '' : $node->render($indent === null ? null : max(0, $indent) + 1),
            $this->childNodes->toArray()
        );

        return implode($render);
    }

    public function setVoid(bool $void): static
    {
        $this->void = $void;

        return $this;
    }

    public function isVoid(): bool
    {
        return $this->void;
    }

    public function getOpenTag(): string
    {
        if (!$this->attributes->isEmpty()) {
            $attributes = sprintf(' %s', $this->attributes);
        }

        return sprintf("<%s%s%s>", strtolower($this->nodeName), $attributes ?? '', $this->isVoid() ? '/' : '');
    }

    public function getCloseTag(): ?string
    {
        return $this->isVoid() ? null : sprintf('</%s>', strtolower($this->nodeName));
    }

    /**
     * @return ElementList<int, ElementInterface>
     */
    public function getChildren(): ElementList
    {
        $filter = array_filter(
            $this->childNodes->toArray(),
            fn (NodeInterface $node) => $node->getNodeType() === NodeTypeEnum::NODE_ELEMENT->value
        );

        // @phpstan-ignore-next-line
        return new ElementList($filter);
    }

    public function getAttribute(string $name, \Stringable|string|null $default = null): ?string
    {
        return $this->attributes->get($name, $default);
    }

    public function getAttributes(): Attributes
    {
        $attributes = array_map(fn ($value) => (string)$value, $this->attributes->toArray());

        return new Attributes($attributes);
    }

    public function hasAttribute(string $name): bool
    {
        return $this->attributes->has($name);
    }

    /**
     * @return array<string>
     */
    public function getAttributeNames(): array
    {
        return $this->attributes->getNames();
    }

    public function setAttribute(string $name, \Stringable|string|null $value): static
    {
        if (strtolower(trim($name)) === 'class') {
            if ($value !== $this->classList) {
                $this->classList->clear();

                $value = ($value !== null) ? $this->classList->add($value) : $this->classList;
            }
        }

        (new ObjectReflector($this->attributes))->invokeMethod('set', $name, $value);

        return $this;
    }

    public function hasAttributes(): bool
    {
        return !$this->attributes->isEmpty();
    }

    public function removeAttribute(string $name): static
    {
        (new ObjectReflector($this->attributes))->invokeMethod('remove', $name);

        return $this;
    }

    /**
     * @param string $selector
     * @return ElementList<int, ElementInterface>
     */
    public function querySelectorAll(string $selector): ElementList
    {
        return (new NodeSelector($this, $selector))->getResult();
    }

    public function querySelector(string $selector): ?ElementInterface
    {
        return $this->querySelectorAll($selector)->first();
    }

    public function matches(string $selector): bool
    {
        $transformer = new Transformer();
        $encodeSelector = $transformer->encodeAttributes($transformer->encodeQuotedStrings($selector));
        $matcher = new Matcher($this, new Tokenizer($encodeSelector));

        return $matcher->matchesNode();
    }

    /**
     * @param string $names
     * @return ElementList<int, ElementInterface>
     */
    public function getElementsByClassName(string $names): ElementList
    {
        $classes = implode('.', array_map('trim', explode(' ', $names)));

        return $this->querySelectorAll(".{$classes}");
    }

    /**
     * @param string $name
     * @return ElementList<int, ElementInterface>
     */
    public function getElementsByTagName(string $name): ElementList
    {
        return $this->querySelectorAll($name);
    }

    public function getElementById(string $id): ?ElementInterface
    {
        return $this->querySelector(sprintf('#%s', $id));
    }

    public function getFirstElementChild(): ?ElementInterface
    {
        return $this->getChildren()->first();
    }

    public function getLastElementChild(): ?ElementInterface
    {
        return $this->getChildren()->last();
    }

    public function getNextElementSibling(): ?ElementInterface
    {
        return $this->getNodeSibling($this->parentElement?->getChildren(), 1);
    }

    public function getPreviousElementSibling(): ?ElementInterface
    {
        return $this->getNodeSibling($this->parentElement?->getChildren(), -1);
    }
}
