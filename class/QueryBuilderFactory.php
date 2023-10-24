<?php

namespace MMantai\QueryBuilder;

use MMantai\QueryBuilder\MySQLQueryBuilder;


class QueryBuilderFactory
{
    public static function create($type)
    {
        switch($type)
        {
            case "mysql": return new MySQLQueryBuilder();
            case "pgsql": return new PostgreSQLQueryBuilder();
        }
    }
}