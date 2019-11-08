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
    
    /**
     * 
     * 
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->connect($options);
        if (array_key_exists("graph", $options)) {
            $this->graph = $graph;
        }
    }
    
    /**
     * 
     * @param string $graph
     * @return \RedisGraphPhp\Client
     */
    final public function setGraph(string $graph): Client
    {
        $this->graph = $graph;
        
        return $this;
    }
    
    /**
     * Executes the query
     * @param string $query
     * @return array
     */
    final public static function run(Cypher $cypher, $graph = ""): Result
    {
        $graph = $graph === "" ? $this->graph : $graph;
        $result = $this->client->executeRaw(["GRAPH.QUERY", $graph, $cypher->getQuery()]);
        
        if (!is_array($result)) {
            throw new Exception("Cypher error: $result");
        }
        
        return new Result($result, $cypher);
    }
    
    /**
     * Connects to the database
     * @return void
     */
    final private static function connect(array $option): void
    {
        $host = $options["host"];
        $port = $options["port"];
        
        $this->client = new PRedisClient("redis://$host:$port");
    }
}