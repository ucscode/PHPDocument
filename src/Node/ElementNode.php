<?php

namespace Ucscode\UssElement\Node;

use Ucscode\UssElement\Collection\Attributes;
use Ucscode\UssElement\Enums\NodeNameEnum;
use Ucscode\UssElement\Collection\ClassList;
use Ucscode\UssElement\Collection\HtmlCollection;
use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Enums\NodeTypeEnum;
use Ucscode\UssElement\Parser\Engine\Matcher;
use Ucscode\UssElement\Parser\Engine\Tokenizer;
use Ucscode\UssElement\Parser\Engine\Transformer;
use Ucscode\UssElement\Parser\NodeSelector;
use Ucscode\UssElement\Parser\Translator\HtmlLoader;
use Ucscode\UssElement\Support\AbstractNode;
use Ucscode\UssElement\Support\Internal\ObjectReflector;

/**
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class ElementNode extends AbstractNode implements ElementInterface
{
    protected readonly ClassList $classList;
    protected string $tagName;
    protected bool $void;
    protected Attributes $attributes;

    public function __construct(string|NodeNameEnum $nodeName, array $attributes = [])
    {
        parent::__construct($nodeName);

        $this->nodePresets($attributes);
    }

    public function getTagName(): string
    {
        return $this->nodeName;
    }

    public function getNodeType(): int
    {
        return NodeTypeEnum::NODE_ELEMENT->value;
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

        (new ObjectReflector($this->childNodes))->invokeMethod('replace', $loadedNodes);

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

    public function getChildren(): HtmlCollection
    {
        $filter = array_filter(
            $this->childNodes->toArray(),
            fn (NodeInterface $node) => $node->getNodeType() === NodeTypeEnum::NODE_ELEMENT->value
        );

        return new HtmlCollection($filter);
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

    public function querySelectorAll(string $selector): HtmlCollection
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

    public function getElementsByClassName(string $names): HtmlCollection
    {
        $classes = implode('.', array_map('trim', explode(' ', $names)));

        return $this->querySelectorAll(".{$classes}");
    }

    public function getElementsByTagName(string $name): HtmlCollection
    {
        return $this->querySelectorAll($name);
    }

    private function nodePresets(array $attributes): void
    {
        $this->tagName = $this->nodeName;
        $this->attributes = new Attributes();
        $this->classList = new ClassList();

        foreach ($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }

        $this->void = in_array(
            $this->nodeName,
            array_map(fn (NodeNameEnum $enum) => $enum->value, NodeNameEnum::voidCases())
        );
    }
}
