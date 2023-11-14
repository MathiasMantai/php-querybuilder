<?php

namespace Mmantai\QueryBuilder;

use Mmantai\QueryBuilder\MySQLQueryBuilder;
use Mmantai\QueryBuilder\PostgreSQLQueryBuilder;

class QueryBuilderFactory
{
    public static function create($type): MySQLQueryBuilder | PostgreSQLQueryBuilder | null
    {
        switch($type)
        {
            case "mysql": return new MySQLQueryBuilder();
            case "pgsql": return new PostgreSQLQueryBuilder();
        }

        return null;
    }
}