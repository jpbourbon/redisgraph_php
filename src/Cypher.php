<?php
namespace RedisGraphPhp;

use RedisGraphPhp\Exceptions\CypherException;

class Cypher
{
    /**
     *
     * @var type string
     */
    private $query;
    
    /**
     *
     * @var type string
     */
    private $tag;
    
    /**
     *
     * @var type string
     */
    private $graph;
    
    /**
     * 
     * @param string $query
     * @param type $tag
     * @param type $graph
     * @return void
     */
    public function __construct(string $query, $tag = "", $graph = "")
    {
        if ($query !== "") {
            $this->query = $query;
        }
        if ($tag !== "") {
            $this->tag = $tag;
        }
        if ($graph !== "") {
            $this->graph = $graph;
        }
        
        $this->validate();
    }
    
    /**
     * Returns query
     * @return string
     */
    final public function getQuery(): ?string
    {
        return $this->query === "" ? null : $this->query;
    }
    
     /**
      * 
      * @return string|null
      */
    final public function getTag(): ?string
    {
        return $this->tag === "" ? null : $this->tag;
    }
    
    /**
     * 
     * @return string|null
     */
    final public function getGraph(): ?string
    {
        return $this->graph === "" ? null : $this->graph;
    }
    
    /**
     * 
     * @param string $graph
     * @return void
     */
    final public function setGraph(string $graph): void
    {
        $this->graph = $graph;
    }
    
    /**
     * 
     * @return void
     * @throws CypherException
     */
    final public function validate(): void
    {
        if (is_null($this->getQuery())) {
            throw new CypherException();
        }
    }
}