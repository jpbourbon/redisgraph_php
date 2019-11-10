<?php
namespace RedisGraphPhp;

use RedisGraphPhp\Helpers;
use RedisGraphPhp\Interfaces\IRecord;
use RedisGraphPhp\Records\RecordFactory;

class RecordSet
{
    /**
     *
     * @var type 
     */
    private $records = [];
    
    /**
     *
     * @var type 
     */
    private $keys = [];
    
    public function __construct(array $keys, array $array)
    {
        $this->keys = $keys;
        
        $this->parse($array);
    }
    
    final public function parse(array $result): void
    {
        $depth = Helpers::array_depth($result);
        
        foreach ($result as $item) {
            // Single values
            if ($depth == 1) {
                $recordType = RecordFactory::SCALAR;
            }
            if ($depth == 4 || $depth == 5) {
                if (!is_array($item[0])) {
                    $recordType = RecordFactory::SCALAR;
                }
                if ($item[1][0] == "type") {
                    $recordType = RecordFactory::RELATIONSHIP;
                } elseif ($item[1][0] == "labels") {
                    $recordType = RecordFactory::NODE;
                }
            }
            // Need to make them item an array to be valid
            $this->records []= RecordFactory::create([$item], $recordType);
        }
    }
    
    /**
     * 
     * @return array
     */
    final public function getRecords(): array
    {
        return $this->records;
    }
    
    /**
     * 
     * @param int $key
     * @return IRecord|null
     */
    final public function getRecord(int $key): ?IRecord
    {
        $return = null;
        if (array_key_exists($key, $this->records)) {
            $return = $this->records[$key];
        }
        
        return $return;      
    }
    
    /**
     * 
     * @return IRecord|null
     */
    final public function firstRecord(): ?IRecord
    {
        $return = null;
        if (array_key_exists(0, $this->records)) {
            $return = $this->records[0];
        }
        
        return $return;      
    }
}