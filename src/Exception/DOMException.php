<?php

namespace Ucscode\UssElement\Exception;

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/API/DOMException
 */

class DOMException extends \Exception
{
    public const INDEX_SIZE_ERR = 1;
    public const HIERARCHY_REQUEST_ERR = 3;
    public const WRONG_DOCUMENT_ERR = 4;
    public const INVALID_CHARACTER_ERR = 5;
    public const NO_MODIFICATION_ALLOWED_ERR = 7;
    public const NOT_FOUND_ERR = 8;
    public const NOT_SUPPORTED_ERR = 9;
    public const INVALID_STATE_ERR = 11;
    public const SYNTAX_ERR = 12;
    public const INVALID_MODIFICATION_ERR = 13;
    public const NAMESPACE_ERR = 14;
    public const INVALID_ACCESS_ERR = 15;
    public const SECURITY_ERR = 18;
    public const NETWORK_ERR = 19;
    public const ABORT_ERR = 20;
    public const URL_MISMATCH_ERR = 21;
    public const QUOTA_EXCEEDED_ERR = 22;
    public const TIMEOUT_ERR = 23;
    public const INVALID_NODE_TYPE_ERR = 24;
    public const DATA_CLONE_ERR = 25;
    
    /**
     * @var array<int, string>
     */
    private array $messages = [
        self::INDEX_SIZE_ERR => "The index is not in the allowed range",
        self::HIERARCHY_REQUEST_ERR => "The operation would yield an incorrect node tree",
        self::WRONG_DOCUMENT_ERR => "The object is in the wrong document",
        self::INVALID_CHARACTER_ERR => "The string contains invalid characters",
        self::NO_MODIFICATION_ALLOWED_ERR => "The object can not be modified",
        self::NOT_FOUND_ERR => "The object can not be found here",
        self::NOT_SUPPORTED_ERR => "The operation is not supported",
        self::INVALID_STATE_ERR => "The object is in an invalid state",
        self::SYNTAX_ERR => "The string did not match the expected pattern",
        self::INVALID_MODIFICATION_ERR => "The object can not be modified in this way",
        self::NAMESPACE_ERR => "The operation is not allowed by Namespaces in XML",
        self::INVALID_ACCESS_ERR => "The object does not support the operation or argument",
        self::SECURITY_ERR => "The operation is insecure",
        self::NETWORK_ERR => "A network error occurred",
        self::ABORT_ERR => "The operation was aborted",
        self::URL_MISMATCH_ERR => "The given URL does not match another URL",
        self::QUOTA_EXCEEDED_ERR => "The quota has been exceeded",
        self::TIMEOUT_ERR => "The operation timed out",
        self::INVALID_NODE_TYPE_ERR => "The supplied node is invalid or has an invalid type",
        self::DATA_CLONE_ERR => "The object can not be cloned",
    ];

    public function __construct(int $code = 0, ?string $message = null, \Throwable $previous = null)
    {
        parent::__construct($message ?? $this->messages[$code] ?? 'Unknown error', $code, $previous);
    }
}