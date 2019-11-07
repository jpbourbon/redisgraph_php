<?php
namespace RedisGraphPhp\Records;

use RedisGraphPhp\Interfaces\IRecord;

class Single implements IRecord
{
    private $recordType;
    
    /**
     * 
     * @param array $returnKeys
     * @param array $item
     */
    public function __construct(array $item, string $recordType)
    {
        $this->recordType = $recordType;
        
        $this->parseRecord($item);
    }
    
    /**
     * 
     * @param array $item
     * @return void
     */
    public function parseRecord(array $item): void
    {
         $this->value = $item[0];
    }
    
    /**
     * 
     * @return string
     */
    final public function getRecordType(): string
    {
        return $this->recordType;
    }
    
    /**
     * 
     * @param string $string
     * @return type
     */
    final public function get(string $string)
    {
        $return = NULL;
        $key = array_search($string, $this->properties);
        
        if ($key !== false && array_key_exists($key, $this->values)) {
            $return = $this->values[$key];
        }
        
        return $return;
    }
}