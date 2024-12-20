<?php

namespace Ucscode\UssElement\Exception;

/**
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class InvalidNodeException extends \InvalidArgumentException
{
    public const NODE_LIST_EXCEPTION = 'All NodeList item must be instance of %s, found %s';
    public const HTML_COLLECTION_EXCEPTION = 'All ElementList item must be instance of %s, found %s';
}
