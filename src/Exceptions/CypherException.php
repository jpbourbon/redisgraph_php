<?php
namespace RedisGraphPhp\Exceptions;

use Exception;

class CypherException extends Exception
{
    /**
     *
     * @var type string
     */
    protected $message = "Malformed cypher query.";
}