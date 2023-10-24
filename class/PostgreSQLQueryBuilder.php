<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

namespace MMantai\QueryBuilder;

use MMantai\QueryBuilder\QueryBuilderUtil;


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