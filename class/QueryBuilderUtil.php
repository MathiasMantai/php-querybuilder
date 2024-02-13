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

    public function getFirstArrayDimension(array $arr)
    {
        return is_array($arr[0]);
    }
    
    public function arrayDimensionsMatch(array $arr)
    {
        //get first dimension
        $dim = is_array($arr[0]) ? 2 : 1;
        
        array_shift($arr);

        foreach($arr as $arrPart)
        {
            if(!is_array($arrPart))
            {
                return false;
            }
        }

        return true;
    }
}