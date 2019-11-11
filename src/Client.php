<?php
namespace RedisGraphPhp;

use Predis\Client as PRedisClient;
use RedisGraphPhp\{Cypher, Result};
use RedisGraphPhp\Exceptions\{CypherException,
    InvalidOptionsException,
    NoGraphDefinedException};

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
     * @var type string
     */
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
     * @throws CypherException
     */
    final public function run(Cypher $cypher): Result
    {
        $graph = $this->getGraph($cypher);
        $cypher->setGraph($graph);
        
        $result = $this->client->executeRaw(["GRAPH.QUERY", $graph, $cypher->getQuery()]);
        
        if (!is_array($result)) {
            throw new CypherException($result);
        }
        
        return new Result($result, $cypher);
    }
    
    /**
     * 
     * @param Cypher $cypher
     * @return array
     */
    final public function explain(Cypher $cypher): array
    {
        $graph = $this->getGraph($cypher);
        $cypher->setGraph($graph);
        
        $result = $this->client->executeRaw(["GRAPH.EXPLAIN", $graph, $cypher->getQuery()]);
        
        return $result;
    }
    
    /**
     * 
     * @param string $graph
     * @return array
     */
    final public function delete(string $graph): array
    {
        $result = $this->client->executeRaw(["GRAPH.DELETE", $graph]);
        
        return [$result];
    }
    
    /**
     * 
     * @param Cypher $cypher
     * @return string
     * @throws NoGraphDefinedException
     */
    final private function getGraph(Cypher $cypher): string
    {
        $return = null;
        if (!is_null($cypher->getGraph())) {
            $return = $cypher->getGraph();
        } elseif (!is_null($this->otherGraph)) {
            $return = $this->otherGraph;
        } elseif (!is_null($this->graph)) {
            $return = $this->graph;
        } else {
            throw new NoGraphDefinedException();
        }
        
        return $return;
    }
    
    /**
     * Connects to the database
     * @return void
     */
    final private function connect(array $options): void
    {
        $this->validateOptions($options);
        $host = $options["host"];
        $port = $options["port"];
        
        $this->client = new PRedisClient("redis://$host:$port");
    }
    
    /**
     * 
     * @param array $options
     * @return void
     * @throws InvalidOptionsException
     */
    final private function validateOptions(array $options): void
    {
        $expected = [
            "host",
            "port"
        ];
        foreach ($expected as $expectItem) {
            if (!in_array($expectItem, array_keys($options))) {
                throw new InvalidOptionsException();
            } elseif ($options[$expectItem] == "") {
                throw new InvalidOptionsException();
            }
            if ($expectItem == "port" && !is_int($options[$expectItem])) {
                throw new InvalidOptionsException();
            }
            if ($expectItem == "host" && !is_string($options[$expectItem])) {
                throw new InvalidOptionsException();
            }
        }
    }
}