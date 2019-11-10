<?php
namespace RedisGraphPhp\Exceptions;

use Exception;

class InvalidOptionsException extends Exception
{
    /**
     *
     * @var type string
     */
    protected $message = "Invalid options passed. Can't initialize Predis.";
}