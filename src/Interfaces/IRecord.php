<?php
namespace RedisGraphPhp\Interfaces;

interface IRecord
{
    function parseRecord(array $item);
    
    function getRecordType();
    
    function getValue(string $string);
}