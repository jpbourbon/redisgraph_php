<?php
namespace RedisGraphPhp;

use RedisGraphPhp\Statistics;
use RedisGraphPhp\Cypher;
use RedisGraphPhp\Helper;
use RedisGraphPhp\RecordSet;
use RedisGraphPhp\Records\RecordFactory;

class Result
{
    private $keys;
    private $recordSets    = [];
    
    /**
     * Constructor
     * @param array $response
     */
    public function __construct(array $result, Cypher $cypher)
    {
        $this->cypher       = $cypher;
        $this->statistics   = new Statistics(array_pop($result));
        $this->keys         = array_shift($result);
        //var_export($result);exit;
        if (!empty($result[0])) {
            $this->parseResults($result[0]);
        }
    }
    
    /**
     * Parses the result of the query execution
     * @param array $result
     * @return void
     */
    final private function parseResults(array $result): void
    {
        foreach ($result as $item) {
            $this->recordSets []= new RecordSet($this->keys, $item);
        }
    }
    
    /**
     * Returns the size of the returned records
     * @return int
     */
    final public function size(): int
    {
        return count($this->recordSets);
    }
    
    /**
     * Returns statistics object from metadata
     * @return RedisGraphStatistics
     */
    final public function getStatistics(): Statistics
    {
        return $this->statistics;
    }
    
    /**
     * Returns the list of keys
     * @return array
     */
    final public function getKeys(): array
    {
        return $this->keys;
    }
    
    /**
     * Returns Cypher object from metadata
     * @return type Cypher
     */
    final public function getCypher(): Cypher
    {
        return $this->cypher;
    }
    
    /**
     * Returns the first record
     * @return Record|null
     */
    final public function firstRecordSet(): ?RecordSet
    {
        $return = null;
        
        if ($this->size() > 0) {
            $return = $this->recordSets[0];
        }
        
        return $return;
    }
    
    /**
     * Return all records
     * @return array
     */
    final public function getRecordSets(): array
    {
        return $this->records;
    }
    
    /**
     * Returns records by index
     * @param int $index
     * @return array
     */
    final public function getRecordSet(int $index): ?RecordSet
    {
        $return = null;
        
        if (array_key_exists($index, $this->records)) {
            $return = $this->records[$index];
        }
        return $return;
    }
    
    /**
     * Returns the value from a single value record
     * @param string $string
     * @return type mixed
     */
    final public function getAllScalar(string $string)
    {
        $return = null;
        $key = array_search($string, $this->keys);
        
        if ($key !== false) {
            $holder = [];
            foreach ($this->recordSets as $recordSet) {
                if (array_key_exists($key, $recordSet->getRecords()) && $recordSet->getRecord($key)->getRecordType() === RecordFactory::SCALAR) {
                    $holder []= ($recordSet->getRecord($key)->getValue());
                }
            }
            if (!empty($holder)) {
                $return = $holder;
            }
        }
        
        return $return;
    }
}
