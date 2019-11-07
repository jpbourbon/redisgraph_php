<?php
namespace RedisGraphPhp;

class Cypher
{
    private $query;
    private $tag;
    
    public function __construct(string $query, $tag = [])
    {
        $this->query = $query;
        $this->tag = $tag;
    }
    
    /**
     * Returns query
     * @return string
     */
    final public function getQuery(): string
    {
        return $this->query;
    }
    
     /**
     * Returns tag
     * @return string
     */
    final public function getTag(): string
    {
        return $this->tag;
    }
}