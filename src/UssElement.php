<?php

namespace Ucscode\UssElement;

use Exception;
use Ucscode\UssElement\Abstract\AbstractUssElement;
use Ucscode\UssElement\Interface\UssElementInterface;

/**
 * Uss Element Builder
 *
 * Basically, DOM Elements are build with tags (such as h1, p, ...) and attributes (class=, style=).
 * Then they have children and parent. That's all!
 * Any other component added to DOM are features to make it more awesome.
 * This class focuses on the basics of building tags, adding attributes and assigning it to another element
 */
class UssElement extends AbstractUssElement
{
    /**
     * Indicate whether the element should have a closing tag or not.
     */
    public function setVoid(bool $void): self
    {
        $this->void = $void;
        return $this;
    }

    /**
     * Confirm if element is void
     */
    public function isVoid(): bool
    {
        return $this->void;
    }

    /**
     * Hide element from browser DOM while still available and accessible
     */
    public function setInvisible(bool $status): self
    {
        $this->invisible = $status;
        return $this;
    }

    /**
     * Check if element is hidden in DOM
     */
    public function isInvisible(): bool
    {
        return $this->invisible;
    }

    /**
     * Checks if an attribute exists on the element.
     *
     * @param string $name The name of the attribute to check.
     * @return bool `true` if the attribute exists, `false` otherwise.
     */
    public function hasAttribute(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    /**
    * Sets the value of an attribute for the HTML element.
    *
    * @param string $name The name of the attribute to set.
    * @param string|null $value The value to assign to the attribute.
    * @return self
    */
    public function setAttribute(string $name, ?string $value = null): self
    {
        $name = $this->sanitizeAttributeContext($name);
        $this->attributes[$name] = $this->normalizeAttribute($value);
        return $this;
    }

    /**
     * Checks if an attribute has a particular value.
     *
     * @param string $name The name of the attribute to check.
     * @param string $value The value to check for.
     * @return bool `true` if the attribute has the specified value, `false` otherwise.
     */
    public function hasAttributeValue(string $name, string $value): bool
    {
        if($this->hasAttribute($name)) {
            $value = $this->normalizeAttribute($value);
            foreach($value as $unit) {
                if(!in_array($unit, $this->attributes[$name])) {
                    return false;
                };
            };
            return !empty($value);
        }
        return false;
    }

    /**
     * Gets the value of an attribute.
     *
     * @param string $name The name of the attribute to retrieve.
     * @return string|null The value of the specified attribute, or null if the attribute does not exist.
     */
    public function getAttribute(string $name): ?string
    {
        return $this->hasAttribute($name) ? implode(" ", $this->attributes[$name]) : null;
    }

    /**
     * Get all element attributes
     *
     * @return array
     */
    public function getAttributes(): array
    {
        $attributes = [];
        foreach($this->attributes as $key => $value) {
            $attributes[$key] = implode(" ", $value);
        }
        return $attributes;
    }

    /**
     * Appends a value to an attribute.
     *
     * @param string $name The name of the attribute to modify.
     * @param string $value The value to append to the attribute.
     * @return self
     */
    public function addAttributeValue(string $name, string $value): self
    {
        $name = $this->sanitizeAttributeContext($name);
        $value = $this->normalizeAttribute($value);
        $merge = array_merge($this->attributes[$name], $value);
        $this->attributes[$name] = array_unique($merge);
        return $this;
    }

    /**
     * Removes a value from an attribute.
     *
     * @param string $name The name of the attribute to modify.
     * @param string $value The value to remove from the attribute.
     * @return self
     */
    public function removeAttributeValue(string $name, string $value): self
    {
        $name = $this->sanitizeAttributeContext($name);
        $value = $this->normalizeAttribute($value);
        $diff = array_diff($this->attributes[$name], $value);
        $this->attributes[$name] = $diff;
        return $this;
    }

    /**
     * Removes an attribute from the element.
     *
     * @param string $name The name of the attribute to remove.
     * @return self
     */
    public function removeAttribute(string $name): self
    {
        if(isset($this->attributes[$name])) {
            unset($this->attributes[$name]);
        }
        return $this;
    }

    /**
     * Sets the inner HTML content of the element.
     *
     * @param string $content The HTML content to set.
     * @return self
     */
    public function setContent(?string $content): self
    {
        array_walk($this->children, fn ($child) => $child->setParent(null));
        $this->children = [];
        $this->content = $content;
        return $this;
    }

    /**
     * Checks if the element has inner HTML content.
     *
     * @return bool `true` if the element has inner HTML content, `false` otherwise.
     */
    public function hasContent(): bool
    {
        return !is_null($this->content);
    }

    /**
     * Gets the inner HTML content of the element.
     *
     * @return string|null The inner HTML content as a string.
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Get the child elements of the current element
     *
     * @return array Containing child elements
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * Generates an HTML string representation of the element and its children.
     *
     * @param bool $indent If true, the generated HTML will be indented for readability.
     * @return string The HTML string representing the element and its children.
     */
    public function getHTML(bool $indent = false): string
    {
        return $this->buildNode($this, $indent ? 0 : null);
    }

    /**
     * @method getParentElement
     */
    public function getParentElement(): ?UssElementInterface
    {
        return $this->parentElement;
    }

    /**
     * @method getParentElement
     */
    public function hasParentElement(): bool
    {
        return !empty($this->parentElement);
    }

    /**
     * @method openTag
     */
    public function getOpeningTag(): string
    {
        $emptyNode = new self($this->nodeName);
        $emptyNode->attributes = $this->attributes;
        return preg_replace("/<\/" . strtolower($this->nodeName) . ">$/", '', $emptyNode->getHTML());
    }

    /**
     * @method closeTag
     */
    public function getClosingTag(): string
    {
        return '</' . strtolower($this->nodeName) . '>';
    }
}
