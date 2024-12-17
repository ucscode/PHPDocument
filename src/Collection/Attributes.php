<?php

namespace Ucscode\PHPDocument\Collection;

use Ucscode\PHPDocument\Exception\InvalidAttributeException;
use Ucscode\PHPDocument\Support\AbstractCollection;

/**
 * @template T
 * @implements IteratorAggregate<string, string>
 */
class Attributes extends AbstractCollection implements \Stringable
{
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Set or update an attribute.
     *
     * @param string $name The attribute name.
     * @param \Stringable|string|null $value The attribute value. Null means the attribute has no value.
     */
    public function set(string $name, \Stringable|string|null $value): static
    {
        $this->items[$this->revise($name)] = $value;

        return $this;
    }

    /**
     * Check if an attribute exists.
     *
     * @param string $name The attribute name to check.
     * @return bool True if the attribute exists, false otherwise.
     */
    public function has(string $name): bool
    {
        return array_key_exists($this->revise($name), $this->items);
    }

    /**
     * Get the value of an attribute.
     *
     * @param string $name The attribute name.
     * @return string|null The attribute value, or null if the attribute does not exist.
     */
    public function get(string $name, \Stringable|string|null $default = null): ?string
    {
        return $this->items[$this->revise($name)] ?? $default;
    }

    /**
     * Remove an attribute.
     *
     * @param string $name The attribute name to remove.
     */
    public function remove(string $name): static
    {
        if (array_key_exists($this->revise($name), $this->items)) {
            unset($this->items[$this->revise($name)]);
        }

        return $this;
    }

    /**
     * Get all the names available in the attribute
     *
     * @return array
     */
    public function getNames(): array
    {
        return array_keys($this->items);
    }

    /**
     * Render the attributes as an HTML-compatible string.
     *
     * @return string The rendered attributes.
     */
    public function render(): string
    {
        $renderedAttrs = [];

        foreach ($this->items as $name => $value) {
            if ($value === null) {
                $renderedAttrs[] = htmlentities($name);
                continue;
            }

            $renderedAttrs[] = sprintf('%s="%s"', htmlentities($name), htmlentities($value));
        }

        return implode(' ', $renderedAttrs);
    }

    /**
     * Trim and make a value case insensitive
     *
     * @param string $name
     * @return string
     */
    private function revise(string $name): string
    {
        return strtolower(trim($name));
    }

    protected function validateItemType(mixed $item)
    {
        if (!$this->canBeString($item)) {
            throw new InvalidAttributeException(
                sprintf(InvalidAttributeException::ATTRIBUTE_VALUE_EXCEPTION, gettype($item))
            );
        }
    }
}
