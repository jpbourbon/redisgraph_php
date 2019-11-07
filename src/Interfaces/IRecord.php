<?php
namespace RedisGraphPhp;

interface IRecord
{
    function parseRecord(array $item);
    
    function getRecordType();
    
    function get(string $string);
}