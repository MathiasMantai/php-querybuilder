<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

namespace MMantai\QueryBuilder;

use MMantai\QueryBuilder\QueryBuilderUtil;

class MySQLQueryBuilder
{
    use QueryBuilderUtil; 
    
    private string $query;

    public function __construct() {
        $this->query = "";
    }

    public function createDB(string $dbName, bool $ifNotExists = false)
    {
        $this->query = "CREATE DATABASE ";

        if($ifNotExists)
            $this->query .= " IF NOT EXISTS ";

        $this->query .= $dbName;
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

            $this->query .= " " . $this->nullable($attributes[$i][2]);

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
    }

    private function nullable($value)
    {
        $this->query .= $value ? "NULL" : "NOT NULL";

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

    public function alter(string $table)
    {
        $this->query .= "ALTER TABLE " . $table;

        return $this;
    }

    public function addColumn(string $columnName)
    {
        $this->query .= "ADD COLUMN ";
    }

    public function dropColumn()
    {

    }

    public function dropTable(string $table)
    {
        $this->query .= "DROP TABLE " . $table;

        return $this;
    }

    public function dropDb(string $db)
    {
        $this->query .= "DROP DATABASE " . $db;

        return $this;
    }

    public function select(array $fields) 
    {
        $this->query .= "SELECT ";

        $count = count($fields);

        for($i = 0; $i < $count; $i++) {

            if(gettype($fields[$i]) == 'array')
            {
                $this->query .= $fields[$i][0];
                if(count($fields[$i]) == 2)
                {
                    $this->query .= " AS " . $fields[$i][1];
                }
            }
            else
                $this->query .= $fields[$i];

            if($i < $count-1) {
                $this->query .= ", ";
            }
        } 

        return $this;
    }
 
    public function selectAll()
    {
        $this->query .= "SELECT *";

        return $this;
    }

    public function from(string $table, string $alias = null) 
    {
        $this->query .= " FROM " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        return $this;
    }

    public function innerJoin(string $table, string $alias = null) 
    {
        $this->query .= " INNER JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        return $this;
    }

    public function leftJoin(string $table, string $alias = null) 
    {
        $this->query .= " LEFT JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        return $this;
    }

    public function rightJoin(string $table, string $alias = null) 
    {
        $this->query .= " RIGHT JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        return $this;
    }

    public function fullJoin(string $table, string $alias = null) 
    {
        $this->query .= " FULL JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        return $this;
    }

    public function naturalJoin(string $table, string $alias = null)
    {
        $this->query .= " NATURAL JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        return $this;
    }

    public function on(string $column1, string $column2)
    {
        $this->query .= " ON " . $column1 . " = " . $column2;

        return $this;
    }

    public function where(string $field, string $operator, string|int|float|bool $value)
    {
        if(gettype($value) == "string")
        {
            $value = $this->formatString($value);
        }

        $this->query .= " WHERE " . $field . " " . $operator . " " . $value;

        return $this;
    }

    public function and(string $field, string $operator, string|int|float|bool $value)
    {
        if(gettype($value) == "string")
        {
            $value = $this->formatString($value);
        }

        $this->query .= " AND " . $field . " " . $operator . " " . $value;

        return $this;
    }

    public function or(string $field, string $operator, string|int|float|bool $value)
    {
        if(gettype($value) == "string")
        {
            $value = $this->formatString($value);
        }

        $this->query .= " OR " . $field . " " . $operator . " " . $value;

        return $this;
    }

    public function orderBy(array $fields, array $order)
    {
        $cnt = count($fields);
        $this->query .= " ORDER BY ";
        //todo throw exception if fields and order arrays dont have the same length

        for($i = 0; $i < $cnt; $i++)
        {
            $this->query .= $fields[$i] . (trim($order[$i]) != ""? " " . $order[$i] : "");

            if($i < $cnt-1)
            {
                $this->query .= ", ";
            }
        }

        return $this;
    }

    public function groupBy(array $fields)
    {
        if(count($fields) > 0)
            $this->query .= " GROUP BY " . implode(", ", $fields);
        
        return $this;
    }

    public function delete(string $table)
    {
        $this->query .= "DELETE FROM " . $table;

        return $this;
    }

    public function update(string $table)
    {
        $this->query .= "UPDATE " . $table;

        return $this;
    }

    public function set(string $column, string|bool|int|float $value)
    {
        if(gettype($value) == "string")
        {
            $value = $this->formatString($value);
        }

        $this->query .= " SET " . $column . " = " . $value;

        return $this;
    }

    public function setMulti(array $columns, array $values)
    {
        $cnt = count($columns);

        //todo throw exception when length of $column and $values is not the same

        for($i = 0; $i < $cnt; $i++)
        {
            $currentColumn = $columns[$i];
            $currentValue = getType($values[$i]) == "string" ? $this->formatString($values[$i]) : $values[$i];
            $this->query .= " SET " . $currentColumn . " = " . $currentValue;

            if($i < $cnt - 1)
            {
                $this->query .= ", ";
            }
        }
    }

    public function insert(string $table, array $columns, array $values)
    {
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