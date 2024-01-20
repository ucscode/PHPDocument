<?php

namespace Ucscode\UssElement\Interface;

interface UssElementInterface
{
    public function setVoid(bool $void): self;

    public function isVoid(): bool;

    public function setAttribute(string $attr, ?string $value = null): self;

    public function getAttribute(string $attr): ?string;

    public function getAttributes(): array;

    public function hasAttribute(string $attr): bool;

    public function removeAttribute(string $attr): self;

    public function addAttributeValue(string $attr, string $value): self;

    public function removeAttributeValue(string $attr, string $value): self;

    public function hasAttributeValue(string $attr, string $value): bool;

    public function setContent(string $content): self;

    public function getContent(): ?string;

    public function hasContent(): bool;

    public function transformElement(string $nodeName): self;

    public function resetContext(): self;

    public function getHTML(bool $indent = false): string;

    public function getOpeningTag(): string;

    public function getClosingTag(): string;

    public function setInvisible(bool $status): self;

    public function isInvisible(): bool;
    
    // Child Characteristics

    public function appendChild(UssElementInterface $child): self;

    public function prependChild(UssElementInterface $child): self;

    public function insertBefore(UssElementInterface $child, UssElementInterface $referenceNode): self;

    public function insertAfter(UssElementInterface $child, UssElementInterface $referenceNode): self;

    public function replaceChild(UssElementInterface $child, UssElementInterface $referenceNode): self;

    public function getFirstChild(): ?UssElementInterface;

    public function getLastChild(): ?UssElementInterface;

    public function getChild(int $index): ?UssElementInterface;

    public function removeChild(UssElementInterface $child): void;

    public function getChildren(): array;
    
    public function sortChildren(callable $callback): void;

    // Parent Characteristics

    public function getParentElement(): ?UssElementInterface;

    public function hasParentElement(): bool;
}
