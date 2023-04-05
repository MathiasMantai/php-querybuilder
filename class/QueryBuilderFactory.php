<?php

namespace Mmantai\QueryBuilder;
use Mmantai\QueryBuilder\MySQLQueryBuilder;


class QueryBuilderFactory
{
    public static function createQueryBuilder($type)
    {
        switch($type)
        {
            case "mysql": return new MySQLQueryBuilder();
            break;
        }
    }
}