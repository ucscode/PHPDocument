<?php

namespace Ucscode\UssElement\Support;

/**
 * This class provides access to encapsulated object properties and methods
 *
 * @internal violates object encapsulation logic
 * @author Uchenna <uche23mail@gmail.com>
 */
class ObjectReflector
{
    public function __construct(protected object $target)
    {

    }

    /**
     * Get the value of a private or protected property
     *
     * @param string $name The name of the property
     * @return mixed
     * @throws \ReflectionException
     */
    public function getProperty(string $name): mixed
    {
        $reflectionProperty = (new \ReflectionObject($this->target))->getProperty($name);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($this->target);
    }

    /**
     * Set the value of a private or protected property.
     *
     * @param string $name
     * @param mixed $value
     * @throws \ReflectionException
     */
    public function setProperty(string $name, mixed $value): void
    {
        $reflectionProperty = (new \ReflectionObject($this->target))->getProperty($name);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->target, $value);
    }

    /**
     * Invoke a private or protected method with zero or more arguments
     *
     * @param string $name The name of the method
     * @param mixed ...$args Values passed by reference
     * @return mixed
     * @throws \ReflectionException
     */
    public function invokeMethod(string $name, mixed ...$args): mixed
    {
        $reflectionMethod = (new \ReflectionObject($this->target))->getMethod($name);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invoke($reflectionMethod->isStatic() ? null : $this->target, ...$args);
    }

    /**
     * Invoke a private or protected method with an array of arguments
     *
     * @param string $name
     * @param mixed[] $args
     * @return mixed
     * @throws \ReflectionException
     */
    public function invokeMethodArray(string $name, array $args = []): mixed
    {
        $reflectionMethod = (new \ReflectionObject($this->target))->getMethod($name);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invokeArgs($reflectionMethod->isStatic() ? null : $this->target, $args);
    }
}
