<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

namespace M2\QueryBuilder;

use M2\QueryBuilder\QueryBuilderInterface;

class MySQLQueryBuilder implements QueryBuilderInterface {
    
    private string $query;

    public function __construct() {
        $this->query = "";
    }

    /**
     * create a database 
     * @param  string $dbName        database name
     * @param  bool   $ifNotExists   "IF NOT EXISTS" clause for create statement
     */
    public function createDB(string $dbName, bool $ifNotExists = false)
    {
        $this->query = "CREATE DATABASE ";

        if($ifNotExists)
            $this->query .= " IF NOT EXISTS ";

        $this->query .= $dbName;
    }

    /**
     * create a table 
     * @param  string $tableName     table name
     * @param  bool   $ifNotExists   "IF NOT EXISTS" clause for create statement
     * @param  array  $attributes    attributes of new table; format is [attribute name, type, null/not null, comment, key constraint (syntax: [type, refTable, refAttribute] or false)]  
     */
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

    /**
     * is the attribute nullable
     * @param  bool     $value   whether the attribute is nullable or not
     * @return string
     */
    private function nullable($value): string
    {
        return $value ? "NULL" : "NOT NULL";
    }

    /**
     * primary key constraint
     * @param $keyName      name of primary key
     * @return string
     */
    private function primaryKey($keyName): string
    {
        return " PRIMARY KEY({$keyName})";
    }

    /**
     * foreign key constraint
     * @param string $keyName       name of foreign key
     * @param string $refTable      table referenced by foreign key
     * @param string $refAttribute  attribute referenced by foreign key
     * @return string
     */
    private function foreignKey($keyName, $refTable, $refAttribute): string
    {
        return " FOREIGN KEY({$keyName}) REFERENCES {$refTable} ({$refAttribute})";
    }

    /**
     * 
     */
    public function alter(string $table, string $alteration)
    {

    }

    /**
     * 
     */
    public function drop(string $table)
    {

    }

    /**
     * SELECT statement
     * @param array $fields     fields to select. to use an alias, use an array, e.g. [["field1", "f1"], "field2] will result in SELECT field1 AS f1, field2

     */    
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

    /**
     * select all (*)
     */    
    public function selectAll()
    {
        $this->query .= "SELECT * ";

        return $this;
    }

    /**
     *  @param string $table
     *  @param string $alias
     */
    public function from(string $table, string $alias = "") {
        $this->query .= " FROM " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        return $this;
    }

    /**
     *  @param string $table
     *  @param string $alias
     *  @param string $on
     */
    public function innerJoin(string $table, string $on = null, string $alias = null) {
        $this->query .= " INNER JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        if($on != null) {
            $this->query .= " ON " . $on;
        }

        return $this;
    }

    /**
     * @param string $table
     * @param string $alias
     * @param string $on
     */
    public function leftJoin(string $table, string $on = null, string $alias = null) {
        $this->query .= " LEFT JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        if($on != null) {
            $this->query .= " ON " . $on;
        }

        return $this;
    }

    /**
     * @param string $table
     * @param string $alias
     * @param string $on
     */
    public function rightJoin(string $table, string $on = null, string $alias = null) {
        $this->query .= " RIGHT JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        if($on != null) {
            $this->query .= " ON " . $on;
        }

        return $this;
    }

    /**
     * @param string $table
     * @param string $alias
     */
    public function fullJoin(string $table, string $alias = null) {
        $this->query .= " FULL JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        return $this;
    }

    /**
     * @param string $table
     */
    public function naturalJoin(string $table)
    {
        $this->query .= " NATURAL JOIN " . $table;

        return $this;
    }


    /**
     * @param string $field
     * @param string $operator
     * @param string|int|float|bool $value
     * 
     * @return [type]
     */
    public function where(string $field, string $operator, string|int|float|bool $value)
    {
        $this->query .= " WHERE " . $field . " " . $operator . " " . $value;

        return $this;
    }

    public function and(string $field, string $operator, string|int|float|bool $value)
    {
        $this->query .= " AND " . $field . " " . $operator . " " . $value;

        return $this;
    }

    public function or(string $field, string $operator, string|int|float|bool $value)
    {
        $this->query .= " OR " . $field . " " . $operator . " " . $value;

        return $this;
    }

    /**
     * @param array $fields
     */
    public function orderBy(array $fields, string $order)
    {
        if(count($fields) > 0)
            $this->query .= " ORDER BY " . implode(",", $fields) . " " . $order;

        return $this;
    }

    /**
     * @param array $fields
     */
    public function groupBy(array $fields)
    {
        if(count($fields) > 0)
            $this->query .= " GROUP BY " . implode(",",$fields);
        
        return $this;
    }

    public function delete(string $table)
    {
        return $this;
    }

    /**
     * @param string $table  - table to update
     * @param array  $update - columns to update in a 2d array. structure should be [x][0] = column and [x][1] = value
     */
    public function update(string $table, array $updateDate)
    {
        return $this;
    }

    /**
     * @param string $table   - table to insert into
     * @param array  $columns - columns to insert
     * @param array  $values  - values to insert 
     */
    public function insert(string $table, array $columns, array $values)
    {
        return $this;
    }

    /**
     * Return the query as a string
     * @return string
     */
    public function get() : string 
    {
        return $this->query;
    }

    public function empty()
    {
        $this->query = "";
    }
}