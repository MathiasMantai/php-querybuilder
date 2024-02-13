<?php

namespace Mmantai\QueryBuilder;

trait QueryBuilderUtil
{
    //WIP
    public function formatString(string $string)
    {
        return "'{$string}'"; 
    }
    
    public function createBindParamList(int $valueCnt)
    {
        return implode(", ", array_fill(0,$valueCnt, '?'));
    }
}