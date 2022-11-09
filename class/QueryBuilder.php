<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */
class QueryBuilder {
    
    private string $query;

    /**
     * @return void
     */
    public function select(array $fields, array $alias = null) {
        $this->query .= "SELECT ";
        $count = count($fields);
        for($i = 0; $i < $count; $i++) {

            $this->query .= $field[$i];

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

    public function where(string $field, string $operator = null, string $value = null) {
        $this->query .= " WHERE " . $field;

        if($operator != null) {
            $this->query .= " " . $operator . " ";
        }

        if($value != null) {
            $this->query .= " " . $value . " ";
        }
    }

    /**
     * Return the query as a string
     */
    public function getQuery() {
        return $this->query;
    }
}