<?php

namespace Kisphp\Exceptions;

class ParameterNotAllowedException extends \Exception
{
    /**
     * @param string $message
     */
    public function __construct($message = null)
    {
        parent::__construct($message, $code = 0, $previous = null);
    }
}
