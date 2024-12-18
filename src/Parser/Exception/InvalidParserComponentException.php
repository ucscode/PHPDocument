<?php

namespace Ucscode\PHPDocument\Parser\Exception;

/**
 * @author Name <email@email.com>
 */
class InvalidParserComponentException extends \InvalidArgumentException
{
    public const INVALID_ATTRIBUTE_DTO_EXCEPTION = 'All AttributeDtoCollection item must be instance of %s, found %s';
}
