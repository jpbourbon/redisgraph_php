<?php
namespace RedisGraphPhp;

class Helpers
{
    final public static function toCamel(string $string): string
    {
        $string = str_replace(' ', '', ucwords(str_replace(' ', ' ', $string)));
        $string = lcfirst($string);
  
        return $string;
    }
    
    final public static function array_depth(array $array): int
    {
    $max_depth = 1;

    foreach ($array as $value) {
        if (is_array($value)) {
            $depth = self::array_depth($value) + 1;

            if ($depth > $max_depth) {
                $max_depth = $depth;
            }
        }
    }

    return $max_depth;
}
}