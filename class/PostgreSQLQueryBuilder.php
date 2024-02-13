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

    public function createDB(string $dbName, array $options = []): self
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

    public function createTable(string $tableName, array $attributes, bool $ifNotExists = false): self
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

    private function primaryKey(string $keyName): self
    {
        $this->query .= " PRIMARY KEY({$keyName})";

        return $this;
    }

    private function foreignKey(string $keyName, string $refTable, string $refAttribute): self
    {
        $this->query .= " FOREIGN KEY({$keyName}) REFERENCES {$refTable} ({$refAttribute})";

        return $this;
    }

    private function nullable(bool $value): self
    {
        $this->query .= $value ? "NULL" : "NOT NULL";

        return $this;
    }


    public function semicolon(): self
    {
        $this->query .= ";";

        return $this;
    }

    public function select(array $fields): self
    {
        if(trim($this->query) != "")
        {
            $this->query .= " ";
        }

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

    public function selectDistinct(array $fields): self
    {
        if(trim($this->query) != "")
        {
            $this->query .= " ";
        }

        $this->query .= "SELECT DISTINCT ";

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

    public function selectAll(): self
    {
        if(trim($this->query) != "")
        {
            $this->query .= " ";
        }

        $this->query .= "SELECT *";

        return $this;
    }

    public function from(string $table, string|null $alias = null): self 
    {
        $this->query .= " FROM " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        return $this;
    }

    public function innerJoin(string $table, string $alias = null): self
    {
        $this->query .= " INNER JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        return $this;
    }

    public function leftJoin(string $table, string $alias = null): self
    {
        $this->query .= " LEFT JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        return $this;
    }

    public function rightJoin(string $table, string $alias = null): self
    {
        $this->query .= " RIGHT JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        return $this;
    }

    public function fullJoin(string $table, string $alias = null): self
    {
        $this->query .= " FULL JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        return $this;
    }

    public function naturalJoin(string $table, string $alias = null, string $type = null): self
    {
        $this->query .= " NATURAL ";

        if($type != null && in_array(strtoupper($type), ["LEFT", "RIGHT", "INNER"]))
        {
            $type = strtoupper($type);
            $this->query .= "{$type} ";
        }

        $this->query .= "JOIN {$table}";

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        return $this;
    }

    public function union(string $table, string $alias = null, array $fields = null): self
    {
        $this->query .= " UNION";

        if($fields != null)
        {
            $fieldsConcat = implode(", ", $fields);
            $this->query .= " SELECT {$fieldsConcat}";
        }
        else
        {
            $this->query .= " SELECT *";
        }

        $this->query .= " FROM {$table}";

        if($alias != null)
        {
            $this->query .= " AS {$alias}";
        }

        return $this;
    }

    public function unionAll(string $table, string $alias = null, array $fields = null): self
    {
        $this->query .= " UNION ALL";

        if($fields != null)
        {
            $fieldsConcat = implode(", ", $fields);
            $this->query .= " SELECT {$fieldsConcat}";
        }
        else
        {
            $this->query .= " SELECT *";
        }

        $this->query .= " FROM {$table}";

        if($alias != null)
        {
            $this->query .= " AS {$alias}";
        }

        return $this;
    }

    public function intersect(string $type = null): self
    {
        $this->query .= " INTERSECT";

        if($type != null && in_array(strtoupper($type), ["DISTINCT", "ALL"]))
        {
            $type = strtoupper($type);
            $this->query .= " {$type}";
        }
        return $this;
    }

    public function except(string $type = null): self
    {
        $this->query .= " EXCEPT";

        if($type != null && in_array(strtoupper($type), ["DISTINCT", "ALL"]))
        {
            $type = strtoupper($type);
            $this->query .= " {$type}";
        }
        return $this;
    }

    public function on(string $column1, string $column2): self
    {
        $this->query .= " ON " . $column1 . " = " . $column2;

        return $this;
    }

    public function where(string $field, string $operator, string|int|float $value): self
    {
        if(gettype($value) == "string" && trim($value) != '?' && !in_array(strtoupper(trim($value)), ["TRUE", "FALSE"]))
        {
            $value = $this->formatString($value);
        }

        $this->query .= " WHERE " . $field . " " . $operator . " " . $value;

        return $this;
    }

    public function and(string $field, string $operator, string|int|float|bool $value): self
    {
        if(gettype($value) == "string" && trim($value) != '?' && !in_array(strtoupper(trim($value)), ["TRUE", "FALSE"]))
        {
            $value = $this->formatString($value);
        }

        $this->query .= " AND " . $field . " " . $operator . " " . $value;

        return $this;
    }

    public function or(string $field, string $operator, string|int|float|bool $value): self
    {
        if(gettype($value) == "string" && trim($value) != '?' && !in_array(strtoupper(trim($value)), ["TRUE", "FALSE"]))
        {
            $value = $this->formatString($value);
        }

        $this->query .= " OR " . $field . " " . $operator . " " . $value;

        return $this;
    }

    public function orderBy(array $fields, array $order): self
    {
        $cnt = count($fields);
        $this->query .= " ORDER BY ";
        //todo throw exception if fields and order arrays dont have the same length

        for($i = 0; $i < $cnt; $i++)
        {
            $this->query .= $fields[$i] . (trim($order[$i]) != "" ? " " . $order[$i] : "");

            if($i < $cnt-1)
            {
                $this->query .= ", ";
            }
        }

        return $this;
    }

    public function groupBy(array $fields): self
    {
        if(count($fields) > 0)
            $this->query .= " GROUP BY " . implode(", ", $fields);
        
        return $this;
    }

    public function delete(string $table): self
    {
        $this->query .= "DELETE FROM " . $table;

        return $this;
    }

    public function update(string $table): self
    {
        $this->query .= "UPDATE " . $table;

        return $this;
    }

    public function set(string $column, string|bool|int|float $value): self
    {
        if(gettype($value) == "string" && trim($value) != '?')
        {
            $value = $this->formatString($value);
        }

        $this->query .= " SET " . $column . " = " . $value;

        return $this;
    }

    public function setMulti(array $columns, array $values): self
    {
        $cnt = count($columns);

        //todo throw exception when length of $column and $values is not the same
        $this->query .= " SET ";
        for($i = 0; $i < $cnt; $i++)
        {
            $currentColumn = $columns[$i];
            $currentValue = getType($values[$i]) == "string" && trim($values[$i]) != '?' ? $this->formatString($values[$i]) : $values[$i];
            $this->query .= $currentColumn . " = " . $currentValue;

            if($i < $cnt - 1)
            {
                $this->query .= ", ";
            }
        }

        return $this;
    }

    public function insert(array $options): self
    {
        $table   = $options["table"] ?? "";
        $columns = $options["columns"] ?? [];
        $values  = $options{"values"} ?? []; 
        $useBindParam = $options["useBindParam"] ?? false;

        //format strings
        array_walk($values, function(&$value) {
            if(gettype($value) == "string" && trim($value) != '?')
            {
                $value = $this->formatString($value);

                if(trim($value) == '')
                {
                    $value = "''";
                }
            }
        });


        $this->query .= "INSERT INTO " . $table . " (" . implode(", ", $columns) . ") VALUES";

        //loop over values and either print values or bind params comma separated 
        $valueString = '';
        $firstDim = $this->getFirstArrayDimension($values);

        if($firstDim == 1)
        {
            $valueString = "(" . (implode(", ", $values)) . ")";
        }
        else if($firstDim == 2 && $this->arrayDimensionsMatch($values))
        {
            $cnt = count($values);
            for($i = 0; $i < $cnt; $i++)
            {
                $valueString = "(" . (implode(", ", $values)) . ")";
                if($i < $cnt - 1)
                {
                    $valueString .= ", ";
                }
            }
        }
        else
        {
            //throw exception
        }

        $this->query .= " {$valueString}";

        return $this;
    }

    //TODO
    public function json_agg(string $value)
    {

    }

    public function json_object_agg()
    {

    }

    //END TODO

    public function get() : string 
    {
        $res = $this->query;
        $this->empty();
        return $res;
    }

    public function empty(): void
    {
        $this->query = "";
    }
}