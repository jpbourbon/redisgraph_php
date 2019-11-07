<?php
namespace RedisGraphPhp\Records;

use RedisGraphPhp\Interfaces\IRecord;

class Relationship implements IRecord
{
    private $recordType;
    
    private $id;
    private $type;
    private $sourceNode;
    private $targetNode;
    private $properties = [];
    private $values = [];
    
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
        $this->id           = $item[0][0][1];
        $this->type         = $item[0][1][1];
        $this->sourceNode   = $item[0][2][1];
        $this->targetNode   = $item[0][3][1];
        
        foreach ($item[0][4][1] as $n) {
            $this->properties   []= $n[0];
            $this->values       []= $n[1];
        }
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
     * @return string
     */
    final public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * 
     * @return string
     */
    final public function getType(): string
    {
        return $this->type;
    }
    
    final public function getLinkedNodes(): string
    {
        return [
            "source" => $this->sourceNode,
            "target" => $this->targetNode
                
        ];
    }
    
    /**
     * 
     * @return string
     */
    final public function getProperties(): string
    {
        return $this->properties;
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