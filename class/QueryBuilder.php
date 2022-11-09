<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */
class QueryBuilder {
    
    private string $query;

    public function __construct() {
        $this->query = "";
    }

    /**
     * $fields = ["field1", "field2"]
     * $alias = ["f1", "f2"]
     * will result in the following query: SELECT field1 AS f1, field2 AS f2
     */
    public function select(array $fields, array $alias = null) {
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

    public function from(string $table, string $alias = null) {
        $this->query .= " FROM " . $table;
        if($alias != null) {
            $this->query .= " AS " . $alias;
        } 
    }

    public function innerJoin(string $table, string $alias = null, string $on = null) {

    }

    public function leftJoin(string $table, string $alias = null, string $on = null) {
        
    }

    public function rightJoin(string $table, string $alias = null, string $on = null) {
        
    }

    public function fullJoin(string $table, string $alias = null) {
        
    }

    public function naturalJoin(string $table, string $alias = null) {
        
    }

    public function where(string $field, string $operator = null, string $value = null) {
        $this->query .= " WHERE " . $field;

        if($operator != null) {
            $this->query .= " " . $operator . " ";
        }

        if($value != null) {
            $this->query .= " " . $value . " ";
        }
    }

    public function orderBy(string $fields) {
        $this->query .= " ORDER BY " . $fields;
    }

    public function groupBy(string $fields) {
        $this->query .= " GROUP BY " . $fields;
    }

    /**
     * Return the query as a string
     */
    public function getQuery() {
        return $this->query;
    }
}