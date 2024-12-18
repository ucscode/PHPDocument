<?php

namespace Ucscode\PHPDocument\Collection;

use PHPUnit\Event\InvalidArgumentException;
use Ucscode\PHPDocument\Support\AbstractCollection;

class ClassList extends AbstractCollection implements \Stringable
{
    public function __toString(): string
    {
        return implode(' ', $this->items);
    }

    /**
     * Add a class to the items if it does not exist
     *
     * @param string $value
     * @return static
     */
    public function add(string $value): static
    {
        $classes = explode(' ', $value);
        foreach ($classes as $class) 
        {
             $class = trim($class);
        if (!empty($class) && !in_array($class, $this->items)) 
        {
            $this->items[] = $class;
        }
    }

    return $this;
    }

    /**
     * Remove a class from the item if it exists
     *
     * @param string $value
     * @return static
     */
    public function remove(string $value): static
    {
        $classes = explode(' ', $value);
        foreach ($classes as $class) 
        {
            $class = trim($class);

            if (!empty($class)) 
            {
                $this->items = array_filter($this->items, function ($item) use ($class) {
                    return $item !== $class;
                });
            }
        }

            return $this;
    }

    /**
     * Replace an existing class with a new one
     *
     * If the previous class does not exists, add a new one
     *
     * @param string $previous
     * @param string $new
     * @return static
     */
    public function replace(string $previous, string $new): static
    {
        $this->items = array_values(array_filter($this->items, function ($item) use ($previous){
            return $item !== $previous;
        }));

        if(!in_array($new, $this->items))
        {
            $this->items[] = $new;
        }

        return $this;
    }

    /**
     * Check if a class exists
     *
     * @param string $value
     * @return static
     */
    public function contains(string $value): bool
    {
        $classes = explode(' ', trim($value));

        foreach ($classes as $class) 
        {
            $class = trim($class); 

            if (!empty($class) && in_array($class, $this->items)) 
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Toggle a class
     *
     * If the class exists, remove it, otherwise, add it
     *
     * @param string $value
     * @return static
     */
    public function toggle(string $value): static
    {
        $classes = explode(' ', trim($value));

        foreach($classes as $class)
        {
            $class = trim($class);
            
            if (!empty($class)) 
            {    
                if(in_array($class, $this->items))
                {
                    $this->remove($class);
                }else{
                    $this->add($class);
                }
            }

            }
        return $this;
    }

    protected function validateItemType(mixed $item)
    {
        if(is_string($item)){
            throw new InvalidArgumentException('Only strings are allowed as classes.');
        }
    }
}
