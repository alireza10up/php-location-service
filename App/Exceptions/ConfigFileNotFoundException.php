<?php

namespace App\Exceptions;

use Exception;

class ConfigFileNotFoundException extends Exception
{
    protected $message = "Config File Not Found";
}