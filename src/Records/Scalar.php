<?php
namespace RedisGraphPhp\Records;

use RedisGraphPhp\Interfaces\IRecord;

class Scalar implements IRecord
{
    /**
     *
     * @var type 
     */
    private $recordType;
    
    /**
     *
     * @var type 
     */
    private $value;
    
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
     * @return type
     */
    final public function getValue($string = "")
    {
        return $this->value;
    }
}