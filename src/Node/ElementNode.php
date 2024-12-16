<?php

namespace Ucscode\PHPDocument\Node;

use Ucscode\PHPDocument\Enums\NodeEnum;
use Ucscode\PHPDocument\Collection\Attributes;
use Ucscode\PHPDocument\Collection\NodeList;
use Ucscode\PHPDocument\Contracts\ElementInterface;
use Ucscode\PHPDocument\Contracts\NodeInterface;
use Ucscode\PHPDocument\Enums\NodeTypeEnum;
use Ucscode\PHPDocument\Support\AbstractNode;

class ElementNode extends AbstractNode implements ElementInterface
{
    protected Attributes $attributes;
    protected bool $void;
    protected string $tagName;

    public function getAttributes(): Attributes
    {
        return $this->attributes;
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

    public function getChildren(): NodeList
    {
        return $this->childNodes->filter(
            fn (NodeInterface $node) => $node->getNodeType() === NodeTypeEnum::NODE_ELEMENT->value
        );
    }

    protected function nodePresets(): void
    {
        $this->tagName = $this->nodeName;
        $this->attributes = new Attributes();
        $this->void = in_array(
            $this->nodeName,
            array_map(fn (NodeEnum $enum) => $enum->value, NodeEnum::voidCases())
        );
    }
}
