<?php

namespace Kisphp\Exceptions;

class MethodNotFoundException extends \Exception
{
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct('Method ' . $message . ' is not declared', $code, $previous);
    }
}
