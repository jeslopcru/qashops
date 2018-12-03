<?php

namespace Exceptions;

use Exception;

class InvalidFileException extends Exception
{
    public function __construct($path) {
        $message = sprintf('The path <%s> can\'t be opened', $path);
        parent::__construct($message);
    }
}