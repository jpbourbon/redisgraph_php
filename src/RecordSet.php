<?php
namespace RedisGraphPhp;

use RedisGraphPhp\Helpers;
use RedisGraphPhp\Interfaces\IRecord;
use RedisGraphPhp\Records\RecordFactory;

class RecordSet
{
    public $records = [];
    private $keys;
    
    public function __construct(array $keys, array $array)
    {
        $this->keys = $keys;
        
        $this->parse($array);
    }
    
    final public function parse(array $result): void
    {
        $depth = Helpers::array_depth($result);
        //var_export($depth);exit;
        
       
        //var_export($result);
        //var_export($depth);
        //exit;
        
        foreach ($result as $item) {
            // Single values
            if ($depth == 1) {
                $recordType = RecordFactory::SINGLE;
            }
            if ($depth == 4 || $depth == 5) {
                if (!is_array($item[0])) {
                    $recordType = RecordFactory::SINGLE;
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
    
    /**
     * 
     * @param string $string
     * @return type
     */
    final public function getValue(string $string)
    {
        $return = null;
        $key = array_search($string, $this->keys);
        
        if ($key !== false && array_key_exists($key, $this->records) && $this->records[$key]->getType() === Record::SINGLE_VALUE) {
            $return = ($this->records[$key])->value;
        }
        
        return $return;
    }
}