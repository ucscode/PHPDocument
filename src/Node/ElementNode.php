<?php

namespace Ucscode\PHPDocument\Node;

use Ucscode\Element\Enums\NodeNameEnum;
use Ucscode\PHPDocument\Collection\Attributes;
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
        return NodeTypeEnum::ELEMENT_NODE->value;
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

    protected function nodePresets(): void
    {
        $this->tagName = $this->nodeName;

        $voidCasesStringMap = array_map(fn (NodeNameEnum $enum) => $enum->value, NodeNameEnum::voidCases());

        $this->void = in_array($this->nodeName, $voidCasesStringMap);
    }
}
