<?php

namespace Chipslays\Telegraph\Exceptions;

use Exception;

class InvalidContentTypeException extends Exception
{
    protected $message = 'Content type should be a string or array of NodeElement objects.';
}
