<?php

namespace Ucscode\PHPDocument\Collection;

use Ucscode\PHPDocument\Support\AbstractCollection;

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
     * @param string|null $value The attribute value. Null means the attribute has no value.
     */
    public function set(string $name, ?string $value): static
    {
        $this->items[$name] = $value;

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
        return array_key_exists($name, $this->items);
    }

    /**
     * Get the value of an attribute.
     *
     * @param string $name The attribute name.
     * @return string|null The attribute value, or null if the attribute does not exist.
     */
    public function get(string $name, mixed $substitute = null): ?string
    {
        return $this->items[$name] ?? $substitute;
    }

    /**
     * Remove an attribute.
     *
     * @param string $name The attribute name to remove.
     */
    public function remove(string $name): static
    {
        if (array_key_exists($name, $this->items)) {
            unset($this->items[$name]);
        }

        return $this;
    }

    /**
     * Prepend a value to an existing attribute.
     *
     * @param string $name The attribute name.
     * @param string $value The value to prepend.
     */
    public function prependValue(string $name, ?string $value): static
    {
        $this->has($name) ?
            $this->set($name, sprintf('%s %s', $value, $this->items[$name])) :
            $this->set($name, $value)
        ;

        return $this;
    }

    /**
     * Append a value to an existing attribute.
     *
     * @param string $name The attribute name.
     * @param string $value The value to append.
     */
    public function appendValue(string $name, ?string $value): static
    {
        $this->has($name) ?
            $this->set($name, sprintf('%s %s', $this->items[$name], $value)) :
            $this->set($name, $value)
        ;

        return $this;
    }

    /**
     * Check if an attribute has a value
     *
     * @param string $name
     * @param string $value
     * @return boolean
     */
    public function hasValue(string $name, string $value): bool
    {
        return in_array(trim($value), explode(' ', $this->get($name)));
    }

    /**
     * Remove a value of an attribute.
     *
     * @param string $key The attribute name.
     * @return string|null The attribute value, or null if the attribute does not exist.
     */
    public function removeValue(string $name, string $value): static
    {
        $valueArray = explode(' ', $this->get($name, ''));

        if (false !== $key = array_search(trim($value), $valueArray)) {
            unset($valueArray[$key]);

            $this->set($name, implode(' ', $valueArray));
        }

        return $this;
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
     * Get all the names available in the attribute
     *
     * @return array
     */
    public function getNames(): array
    {
        return array_keys($this->items);
    }
}
