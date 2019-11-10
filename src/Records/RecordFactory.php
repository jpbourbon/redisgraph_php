<?php
namespace RedisGraphPhp\Records;

use RedisGraphPhp\Interfaces\iRecord;
use RedisGraphPhp\Records\Node;
use RedisGraphPhp\Records\Relationship;
use RedisGraphPhp\Records\Scalar;

class RecordFactory
{
    const NODE          = "node";
    const RELATIONSHIP  = "relationship";
    const SCALAR        = "scalar";
    
    /**
     * This is the factory for the specific kinds of Records
     * @param array $item
     * @param string $type
     * @return iRecord
     */
    final public static function create(array $item, string $recordType): iRecord
    {
        $return = null;
        switch ($recordType) {
            case self::SCALAR:
                $return = new Scalar($item, $recordType);
                break;
            case self::NODE;
                $return = new Node($item, $recordType);
                break;
            case self::RELATIONSHIP;
                $return = new Relationship($item, $recordType);
                break;
                
        }
        
        return $return;
    }
}