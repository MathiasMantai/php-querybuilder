<?php

namespace Mmantai\QueryBuilder;

interface QueryBuilderInterface 
{

    /**
     * 
     */
    public function createDB(string $dbName, bool $ifNotExists = false): void;

    /**
     * 
     */
    public function createTable($tableName, $ifNotExists, $tableData);

    /**
     * 
     */
    public function alter(string $table, string $alteration);

    /**
     * 
     */
    public function drop(string $table);

    /**
     * @param array $columns - columns to select
     */
    public function select(array $fields):void;

    /**
     * @return void
     */
    public function selectAll():void;

    /**
     * @param string $table - table to choose
     */
    public function from(string $table, string $alias);

    /**
     * @param string $where - where clause
     */
    public function where(string $field, string $operator, string $value) : void;

    /**
     * @param string $table - table to join
     * @param string $on    - on clause for join
     */
    public function innerJoin(string $table, string $on);

    /**
     * @param string $table - table to join
     * @param string $on    - on clause for join
     */
    public function leftJoin(string $table, string $on);

    /**
     * @param string $table - table to join
     * @param string $on    - on clause for join
     */
    public function rightJoin(string $table, string $on);

    /**
     * @param string $table - table to join
     * @param string $on    - on clause for join
     */
    public function groupBy(array $groupBy);

    /**
     * @param string $table - table to join
     * @param string $on    - on clause for join
     */
    public function orderBy(array $orderBy, string $order);

    /**
     * @param string $table   - table to insert into
     * @param array  $columns - columns to insert
     * @param array  $values  - values to insert 
     */
    public function insert(string $table, array $columns, array $values);

    /**
     * @param string $table  - table to update
     * @param array  $update - columns to update in a 2d array. structure should be [x][0] = column and [x][1] = value
     */
    public function update(string $table, array $updateDate);

    /**
     * @param string $table - table to delete from
     */
    public function delete(string $table);

    /**
     * @param string $table - table to join
     * @param string $on    - on clause for join
     */
    public function getQuery();

    /**
     * empty the query 
     */
    public function emptyQuery();
}