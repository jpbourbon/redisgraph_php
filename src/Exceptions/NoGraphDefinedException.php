<?php
namespace RedisGraphPhp\Exceptions;

use Exception;

class NoGraphDefinedException extends Exception
{
    /**
     *
     * @var type string
     */
    protected $message = "No graph was defined. Please define one either in the connection options, the cypher query or the chained method.";
}