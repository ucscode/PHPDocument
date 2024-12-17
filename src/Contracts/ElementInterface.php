<?php

namespace Ucscode\PHPDocument\Contracts;

use Ucscode\PHPDocument\Collection\HtmlCollection;

interface ElementInterface extends NodeInterface
{
    public function setInnerHtml(string $innerHTML): static;
    public function getInnerHtml(): string;
    public function setVoid(bool $void): static;
    public function isVoid(): bool;
    public function getOpenTag(): string;
    public function getCloseTag(): ?string;
    public function getChildren(): HtmlCollection;
    public function getAttribute(string $name): ?string;
    public function getAttributeNames(): array;
    public function hasAttribute(string $name): bool;
    public function setAttribute(string $name, \Stringable|string|null $value): static;
}
