<?php

namespace Ucscode\UssElement\Collection;

use Ucscode\UssElement\Exception\InvalidAttributeException;
use Ucscode\UssElement\Abstraction\AbstractCollection;

/**
 * An instance of this class contains attribute names and values for an element
 *
 * @extends AbstractCollection<string, string|null>
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class Attributes extends AbstractCollection implements \Stringable
{
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Check if an attribute exists.
     *
     * @param string $name The attribute name to check.
     * @return bool True if the attribute exists, false otherwise.
     */
    public function has(string $name): bool
    {
        return array_key_exists($this->insensitive($name), $this->items);
    }

    /**
     * Get the value of an attribute.
     *
     * @param string $name The attribute name.
     * @return string|null The attribute value, or null if the attribute does not exist.
     */
    public function get(string $name, \Stringable|string|null $default = null): ?string
    {
        return $this->items[$this->insensitive($name)] ?? $default;
    }

    /**
     * Get all the names available in the attribute
     *
     * @return array<string>
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
     * Set or update an attribute.
     *
     * @param string $name The attribute name.
     * @param \Stringable|string|null $value The attribute value. Null means the attribute has no value.
     */
    protected function set(string $name, \Stringable|string|null $value): static
    {
        $this->items[$this->insensitive($name)] = $value;

        return $this;
    }

    /**
     * Remove an attribute.
     *
     * @param string $name The attribute name to remove.
     */
    protected function remove(string $name): static
    {
        if (array_key_exists($this->insensitive($name), $this->items)) {
            unset($this->items[$this->insensitive($name)]);
        }

        return $this;
    }

    /**
     * Trim and make a value case insensitive
     *
     * @param string $name
     * @return string
     */
    protected function insensitive(string $name): string
    {
        return strtolower(trim($name));
    }

    protected function validateItem(mixed $item): void
    {
        if (!$this->isStringable($item)) {
            throw new InvalidAttributeException(
                sprintf(InvalidAttributeException::ATTRIBUTE_VALUE_EXCEPTION, gettype($item))
            );
        }
    }
}
