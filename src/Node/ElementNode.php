<?php

namespace Ucscode\PHPDocument\Node;

use Ucscode\PHPDocument\Enums\NodeEnum;
use Ucscode\PHPDocument\Collection\Attributes;
use Ucscode\PHPDocument\Collection\ClassList;
use Ucscode\PHPDocument\Collection\HtmlCollection;
use Ucscode\PHPDocument\Contracts\ElementInterface;
use Ucscode\PHPDocument\Contracts\NodeInterface;
use Ucscode\PHPDocument\Enums\NodeTypeEnum;
use Ucscode\PHPDocument\Support\AbstractNode;

class ElementNode extends AbstractNode implements ElementInterface
{
    public readonly ClassList $classList;
    protected Attributes $attributes;
    protected bool $void;
    protected string $tagName;

    public function __construct(string|NodeEnum $nodeName, array $attributes = [])
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

    public function render(): string
    {
        return sprintf('%s%s%s', $this->getOpenTag(), $this->getInnerHtml(), $this->getCloseTag());
    }

    public function setInnerHtml(string $html): static
    {
        return $this;
    }

    public function getInnerHtml(): string
    {
        $renderedNodes = array_map(
            fn (NodeInterface $node) => $node->render(),
            $this->childNodes->toArray()
        );

        return implode($renderedNodes);
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
        return sprintf("<%s %s%s>", strtolower($this->nodeName), $this->attributes->render(), $this->isVoid() ? '' : '/');
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

    public function hasAttribute(string $name): bool
    {
        return $this->attributes->has($name);
    }

    public function getAttributeNames(): array
    {
        return array_keys($this->attributes->toArray());
    }

    public function setAttribute(string $name, \Stringable|string|null $value): static
    {
        if (strtolower(trim($name)) === 'class' && $value !== $this->classList) {
            $value = $this->classList->add($value ?? '');
        }

        $this->attributes->set($name, $value);

        return $this;
    }

    private function nodePresets(array $attributes): void
    {
        $this->tagName = $this->nodeName;
        $this->classList = new ClassList();
        $this->attributes = new Attributes($attributes);
        $this->void = in_array(
            $this->nodeName,
            array_map(fn (NodeEnum $enum) => $enum->value, NodeEnum::voidCases())
        );
    }
}
