<?php

namespace App\Exceptions;

use Exception;

class SingletonException extends Exception
{
    protected $message = 'Singleton class cannot be instantiated multiple times.';
}