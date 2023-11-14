<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

namespace Mmantai\QueryBuilder;

use Mmantai\QueryBuilder\QueryBuilderUtil;


class PostgreSQLQueryBuilder
{
    use QueryBuilderUtil;

    private string $query;

    public function __construct()
    {
        $this->query = "";
    }

    public function createDB(string $dbName, $options = [])
    {
        $this->query = "CREATE DATABASE ";
        $this->query .= $dbName;

        if(count($options) > 0)
        {
            $this->query .= " ";

            foreach($options as $key => $value)
            {
                $key = strtoupper($key);
                $this->query .= "{$key} {$value}";
            }
        }

        return $this;
    }

    public function createTable($tableName, $attributes, $ifNotExists = false)
    {
        $this->query = " CREATE TABLE ";
        if($ifNotExists)
            $this->query .= " IF NOT EXISTS ";
        
        $this->query .= $tableName;

        //attributes
        $this->query .= " ( ";

        $cnt = count($attributes);
        for($i = 0; $i < $cnt; $i++)
        {
            $this->query .= "{$attributes[$i][0]} {$attributes[$i][1]}";

            $this->query .= " ";
            $this->nullable($attributes[$i][2]);

            //comment
            if(trim($attributes[$i][3]) != "")
                $this->query .= " COMMENT '{$attributes[$i][3]}'";

            //key constraints
            if(gettype($attributes[$i][4]) == 'array')
            {
                switch($attributes[$i][4][0])
                {
                    case 0: $this->query .= ", " . $this->primaryKey($attributes[$i][0]);
                    break;
                    case 1: $this->query .= ", " . $this->foreignKey($attributes[$i][0], $attributes[$i][4][1], $attributes[$i][4][2]);
                    break;
                }
            }

            //separator
            if($i < $cnt-1)
                $this->query .= ", ";
        }

        $this->query .= " );";

        return $this;
    }

    private function primaryKey($keyName)
    {
        $this->query .= " PRIMARY KEY({$keyName})";

        return $this;
    }

    private function foreignKey($keyName, $refTable, $refAttribute)
    {
        $this->query .= " FOREIGN KEY({$keyName}) REFERENCES {$refTable} ({$refAttribute})";

        return $this;
    }

    private function nullable($value)
    {
        $this->query .= $value ? "NULL" : "NOT NULL";

        return $this;
    }


    public function semicolon()
    {
        $this->query .= ";";

        return $this;
    }

    public function get() : string 
    {
        $res = $this->query;
        $this->empty();
        return $res;
    }

    public function empty()
    {
        $this->query = "";
    }
}