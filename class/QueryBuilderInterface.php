<?php

interface QueryBuilderInterface 
{
    /**
     * @param array $columns - columns to select
     */
    public function select(array $columns);

    /**
     * @param string $table - table to choose
     */
    public function from(string $table);

    /**
     * @param string $where - where clause
     */
    public function where(string $where);

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
    public function groupBy(string $groupBy);

    /**
     * @param string $table - table to join
     * @param string $on    - on clause for join
     */
    public function orderBy(string $orderBy);

    /**
     * @param string $table - table to join
     * @param string $on    - on clause for join
     */
    public function getQuery();

    /**
     * @param string $table - table to join
     * @param string $on    - on clause for join
     */
    public function setQuery();
}