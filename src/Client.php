<?php
namespace RedisGraphPhp;

use Predis\Client as PRedisClient;
use RedisGraphPhp\Cypher;
use RedisGraphPhp\Result;
use Exception;

class Client
{   
    /**
     *
     * @var type PRedisClient
     */
    private $client;
    
    /**
     *
     * @var type string
     */
    private $graph;
    
    private $otherGraph;
    
    /**
     * 
     * 
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->connect($options);
        if (array_key_exists("graph", $options)) {
            $this->graph = $options["graph"];
        }
    }
    
    /**
     * 
     * @param string $graph
     * @return \RedisGraphPhp\Client
     */
    final public function setGraph(string $graph): Client
    {
        $this->otherGraph = $graph;
        
        return $this;
    }
    
    /**
     * 
     * @param Cypher $cypher
     * @return Result
     * @throws Exception
     */
    final public function run(Cypher $cypher): Result
    {
        
        $graph = $this->getGraph($cypher);
        $cypher->setGraph($graph);
        $result = $this->client->executeRaw(["GRAPH.QUERY", $graph, $cypher->getQuery()]);
        
        if (!is_array($result)) {
            throw new Exception("Cypher error: $result");
        }
        
        return new Result($result, $cypher);
    }
    
    /**
     * 
     * @return string
     * @throws Exception
     */
    final private function getGraph(Cypher $cypher): string
    {
        $return = null;
        if (!is_null($cypher->getGraph())) {
            $return = $cypher->getGraph();
        } elseif (!is_null($this->otherGraph)) {
            $reutn = $this->otherGraph;
        } elseif (!is_null($this->graph)) {
            $return = $this->graph;
        } else {
            throw new Exception("Please define a graph");
        }
        
        return $return;
    }
    
    /**
     * Connects to the database
     * @return void
     */
    final private function connect(array $options): void
    {
        $host = $options["host"];
        $port = $options["port"];
        
        $this->client = new PRedisClient("redis://$host:$port");
    }
}