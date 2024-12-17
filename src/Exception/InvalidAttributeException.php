<?php

namespace Ucscode\PHPDocument\Exception;

class InvalidAttributeException extends \InvalidArgumentException
{
    public const ATTRIBUTE_VALUE_EXCEPTION = 'Attribute values must be of type stringable, %s given';
}
