<?php

namespace Exceptions;

use Exception;

class FileIsEmptyException extends Exception
{
    public function __construct($path) {
        $message = sprintf('The file <%s> is empty', $path);
        parent::__construct($message);
    }
}