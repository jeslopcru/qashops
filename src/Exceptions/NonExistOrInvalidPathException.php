<?php

namespace Exceptions;

use Exception;

class NonExistOrInvalidPathException extends Exception
{
    public function __construct($path) {
        $message = sprintf('The path <%s> doesn\'t exist or is invalid', $path);
        parent::__construct($message);
    }
}