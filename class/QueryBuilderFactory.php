<?php

namespace M2\QueryBuilder;

use M2\QueryBuilder\MySQLQueryBuilder;


class QueryBuilderFactory
{
    public static function create($type)
    {
        switch($type)
        {
            case "mysql": return new MySQLQueryBuilder();
            break;
        }
    }
}