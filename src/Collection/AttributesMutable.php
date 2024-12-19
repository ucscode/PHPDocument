<?php

namespace Ucscode\PHPDocument\Collection;

/**
 * A mutable version of attributes
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class AttributesMutable extends Attributes
{
    /**
     * Set or update an attribute.
     *
     * @param string $name The attribute name.
     * @param \Stringable|string|null $value The attribute value. Null means the attribute has no value.
     */
    public function set(string $name, \Stringable|string|null $value): static
    {
        $this->items[$this->insensitive($name)] = $value;

        return $this;
    }

    /**
     * Remove an attribute.
     *
     * @param string $name The attribute name to remove.
     */
    public function remove(string $name): static
    {
        if (array_key_exists($this->insensitive($name), $this->items)) {
            unset($this->items[$this->insensitive($name)]);
        }

        return $this;
    }
}
