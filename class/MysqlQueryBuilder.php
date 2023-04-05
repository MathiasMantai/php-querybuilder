<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

namespace Mmantai\QueryBuilder;

class MySQLQueryBuilder implements QueryBuilderInterface {
    
    private string $query;

    public function __construct() {
        $this->query = "";
    }

    /**
     * 
     */
    public function create(array $data)
    {

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
     * @param array $fields
     * @param array $ailas
     * @return void
     * $fields = ["field1", "field2"]
     * $alias = ["f1", "f2"]
     * will result in the following query: SELECT field1 AS f1, field2 AS f2
     */    
    public function select(array $fields, array $alias = null) : void {
        $this->query .= "SELECT ";

        $count = count($fields);

        for($i = 0; $i < $count; $i++) {

            $this->query .= $fields[$i];

            if($alias != null && array_key_exists($i, $alias)) {
                $this->query .= " AS " . $alias[$i];
            }

            if($i < $count-1) {
                $this->query .= ", ";
            }
        } 
    }

    /**
     * @param string $table
     * @param string $where
     * @return void
     */    
    public function selectAll(string $table, string $where = null): void {
        $this->query .= "SELECT * FROM " . $table;
        if($where != null) {
            $this->query .= " WHERE " . $where;
        }
    }

    /**
     *  @param string $table
     *  @param string $alias
     *  @return void
     */
    public function from(string $table, string $alias = null) : void {
        $this->query .= " FROM " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }
    }

    /**
     *  @param string $table
     *  @param string $alias
     *  @param string $on
     *  @return void
     */
    public function innerJoin(string $table, string $alias = null, string $on = null) : void {
        $this->query .= " INNER JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        if($on != null) {
            $this->query .= " ON " . $on;
        }
    }

    /**
     * @param string $table
     * @param string $alias
     * @param string $on
     * @return void
     */
    public function leftJoin(string $table, string $alias = null, string $on = null) : void {
        $this->query .= " LEFT JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        if($on != null) {
            $this->query .= " ON " . $on;
        }
    }

    /**
     * @param string $table
     * @param string $alias
     * @param string $on
     * @return void
     */
    public function rightJoin(string $table, string $alias = null, string $on = null) : void {
        $this->query .= " RIGHT JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }

        if($on != null) {
            $this->query .= " ON " . $on;
        }
    }

    /**
     * @param string $table
     * @param string $alias
     * @return void
     */
    public function fullJoin(string $table, string $alias = null) : void {
        $this->query .= " FULL JOIN " . $table;

        if($alias != null) {
            $this->query .= " AS " . $alias;
        }
    }

    /**
     * @param string $table
     * @return void
     */
    public function naturalJoin(string $table) : void {
        $this->query .= " NATURAL JOIN " . $table;
    }


    /**
     * @param string $field
     * @param string $operator
     * @param string $value
     * @return void
     */
    public function where(string $field, string $operator = null, string $value = null) : void {

        $this->query .= " WHERE " . $field;

        if($operator != null) {
            $this->query .= " " . $operator . " ";
        }

        if($value != null) {
            $this->query .= " " . $value . " ";
        }
    }

    /**
     * @param string $fields
     * @return void
     */
    public function orderBy(string $fields) : void {
        $this->query .= " ORDER BY " . $fields;
    }

    /**
     * @param string $fields
     * @return void
     */
    public function groupBy(string $fields) : void {
        $this->query .= " GROUP BY " . $fields;
    }

    public function delete(string $table)
    {

    }

    /**
     * @param string $table  - table to update
     * @param array  $update - columns to update in a 2d array. structure should be [x][0] = column and [x][1] = value
     */
    public function update(string $table, array $updateDate)
    {

    }

        /**
     * @param string $table   - table to insert into
     * @param array  $columns - columns to insert
     * @param array  $values  - values to insert 
     */
    public function insert(string $table, array $columns, array $values)
    {

    }

    /**
     * Return the query as a string
     * @return string
     */
    public function getQuery() : string {
        return $this->query;
    }

    public function emptyQuery() : void {
        $this->query = null;
    }
}