<?php
namespace RedisGraphPhp;

use RedisGraphPhp\Helpers;

class Statistics
{
    const NODES_CREATED = "Nodes created";
    const RELATIONSHIPS_CREATED = "Relationships created";
    const QUERY_INT_EXEC_TIME = "Query internal execution time";
    const LABELS_ADDED = "Labels added";
    
    private $stats = [];
    
    public function __construct(array $stats)
    {
        $this->parse($stats);
    }
    /**
     * Return stats as string
     * @return string
     */
    final public function __toString(): string
    {
        $stats = [];
        foreach($this->stats as $key => $value) {
            $stats []= "$key: $value";
        }
        
        return implode(" | ", $stats);
    }
    
    /**
     * Return stats as array
     * @return array
     */
    final public function all(): array
    {
        return $this->stats;
    }
    
    /**
     * Parses the stats array
     * @param array $stats
     * @return void
     */
    final private function parse(array $stats): void
    {
        foreach ($stats as $statsItem) {
            $statsItem = explode(": ", $statsItem);
            switch ($statsItem[0]) {
                case self::NODES_CREATED:
                case self::RELATIONSHIPS_CREATED:
                case self::LABELS_ADDED:
                    $statsItem[1] = (int)$statsItem[1];
                    break;
                case self::QUERY_INT_EXEC_TIME:
                    $statsItem[0] = "Query time";
                    $statsItem[1] = explode(" ", $statsItem[1]);
                    $statsItem[1] = (float)$statsItem[1][0];
                    break;
                default:
                    // TEMPORARY
                    $statsItem[0] = "CONST NEEDED - " . $statsItem[0];
            }
            $this->stats[$statsItem[0]] = $statsItem[1];
            
        }
    }
    
    /**
     * Returns specific values from statistics
     * @param string $method
     * @param type $args
     * @return mixed
     */
    final public function __call(string $method, $args = [])
    {
        $return = NULL;
        
        foreach ($this->stats as $key => $value) {
            if ($method == Helpers::toCamel($key)) {
                $return = $value;
                break;
            }
        }
        
        return $return;
    }
}