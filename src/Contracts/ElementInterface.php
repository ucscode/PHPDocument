<?php

namespace Ucscode\PHPDocument\Contracts;

use Ucscode\PHPDocument\Collection\NodeList;

interface ElementInterface
{
    public function setInnerHtml(string $innerHTML): static;
    public function getInnerHtml(): string;
    public function setVoid(bool $void): static;
    public function isVoid(): bool;
    public function getOpenTag(): string;
    public function getCloseTag(): ?string;
}
