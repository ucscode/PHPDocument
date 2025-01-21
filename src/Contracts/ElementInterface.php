<?php

namespace Ucscode\UssElement\Contracts;

use Ucscode\UssElement\Collection\Attributes;
use Ucscode\UssElement\Collection\ElementList;
use Ucscode\UssElement\Collection\ClassList;

/**
 * The base inteface for all element nodes
 *
 * @method ElementInterface getParentNode()
 * @method ElementInterface cloneNode(bool $deep)
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
interface ElementInterface extends NodeInterface
{
    /**
     * Gets the tag name of the element.
     *
     * @return string The tag name of the element.
     */
    public function getTagName(): string;

    /**
     * Sets the inner HTML content of the element.
     *
     * @param string $innerHTML The inner HTML content.
     * @return static
     */
    public function setInnerHtml(string $innerHTML): static;

    /**
     * Gets the inner HTML content of the element.
     *
     * @return string The inner HTML content.
     */
    public function getInnerHtml(): string;

    /**
     * Marks the element as void (without a closing tag).
     *
     * @param bool $void Whether the element is void.
     * @return static
     */
    public function setVoid(bool $void): static;

    /**
     * Checks if the element is marked as void.
     *
     * @return bool True if the element is void, otherwise false.
     */
    public function isVoid(): bool;

    /**
     * Gets the opening tag of the element as a string.
     *
     * @return string The opening tag of the element.
     */
    public function getOpenTag(): string;

    /**
     * Gets the closing tag of the element as a string, if applicable.
     *
     * @return string|null The closing tag, or null if the element is void.
     */
    public function getCloseTag(): ?string;

    /**
     * Gets a collection of child elements.
     *
     * @return ElementList<int, ElementInterface> The collection of child elements.
     */
    public function getChildren(): ElementList;

    /**
     * Gets the value of a specific attribute.
     *
     * @param string $name The attribute name.
     * @return string|null The attribute value, or null if not set.
     */
    public function getAttribute(string $name): ?string;

    /**
     * Gets all attributes of the element.
     *
     * @return Attributes The attributes of the element.
     */
    public function getAttributes(): Attributes;

    /**
     * Gets the names of all attributes on the element.
     *
     * @return array<string> The list of attribute names.
     */
    public function getAttributeNames(): array;

    /**
     * Checks if a specific attribute exists on the element.
     *
     * @param string $name The attribute name.
     * @return bool True if the attribute exists, otherwise false.
     */
    public function hasAttribute(string $name): bool;

    /**
     * Checks if the element has any attributes.
     *
     * @return bool True if the element has attributes, otherwise false.
     */
    public function hasAttributes(): bool;

    /**
     * Sets an attribute on the element.
     *
     * @param string $name The attribute name.
     * @param \Stringable|string|null $value The attribute value.
     * @return static
     */
    public function setAttribute(string $name, \Stringable|string|null $value): static;

    /**
     * Removes a specific attribute from the element.
     *
     * @param string $name The attribute name.
     * @return static
     */
    public function removeAttribute(string $name): static;

    /**
     * Finds the first element matching a CSS selector.
     *
     * @param string $selector The CSS selector.
     * @return ElementInterface|null The matching element, or null if not found.
     */
    public function querySelector(string $selector): ?ElementInterface;

    /**
     * Finds all elements matching a CSS selector.
     *
     * @param string $selector The CSS selector.
     * @return ElementList<int, ElementInterface> The collection of matching elements.
     */
    public function querySelectorAll(string $selector): ElementList;

    /**
     * Gets a collection of class list of the element.
     *
     * @return ClassList The class list of the element.
     */
    public function getClassList(): ClassList;

    /**
     * Checks if the current element matches a CSS selector.
     *
     * @param string $selector The CSS selector.
     * @return bool True if the element matches, otherwise false.
     */
    public function matches(string $selector): bool;

    /**
     * Finds all elements with specific class names.
     *
     * @param string $names The class names, separated by spaces.
     * @return ElementList<int, ElementInterface> The collection of matching elements.
     */
    public function getElementsByClassName(string $names): ElementList;

    /**
     * Finds all elements with a specific tag name.
     *
     * @param string $name The tag name.
     * @return ElementList<int, ElementInterface> The collection of matching elements.
     */
    public function getElementsByTagName(string $name): ElementList;
    
    /**
     * Get an element by id
     *
     * @param string $id
     * @return ElementInterface|null
     */
    public function getElementById(string $id): ?ElementInterface;

    /**
     * Get the first element child of the target node
     *
     * @return ElementInterface|null
     */
    public function getFirstElementChild(): ?ElementInterface;
    
    /**
     * Get the last element child of the target node
     *
     * @return ElementInterface|null
     */
    public function getLastElementChild(): ?ElementInterface;
    
    /**
     * Get the next element sibling of the target node
     *
     * @return ElementInterface|null
     */
    public function getNextElementSibling(): ?ElementInterface;
    
    /**
     * Get the last element sibling of the target node
     *
     * @return ElementInterface|null
     */
    public function getPreviousElementSibling(): ?ElementInterface;
}
