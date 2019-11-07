<?php
namespace RedisGraphPhp;

use Predis\Client as PRedisClient;
use RedisGraphPhp\Cypher;
use RedisGraphPhp\Result;
use Exception;

class RedisGraph
{
    /**
     *
     * @var type Predis\Client
     */
    private static $client;
    /**
     *
     * @var type RedisGraph
     */
    private static $instance = null;
    
    /**
     * Executes the query
     * @param string $query
     * @return array
     */
    final public static function run(Cypher $cypher): Result
    {
        self::getInstance();
        $redis = self::$client;
        $graph = "test";
        $result = $redis->executeRaw(["GRAPH.QUERY", $graph, $cypher->getQuery()]);
        
        if (!is_array($result)) {
            throw new Exception("Cypher error: $result");
        }
        
        return new Result($result, $cypher);
    }
    
    /**
     * Connects to the database
     * @return void
     */
    final private static function connect(): void
    {
        $host =config("database.redis.default.host");
        $port = config("database.redis.default.port");
        
        self::$client = new PRedisClient("redis://$host:$port");
    }
    
    /**
     * Returns the instance
     * @return \App\Connectors\RedisGraph
     */
    final private static function getInstance(): Client
    {
        if (self::$instance == null)
        {
            self::$instance = new RedisGraph();
            self::connect();
        }
 
        return self::$instance;
    }
}