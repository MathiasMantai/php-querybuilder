<?php

use QueryBuilder\QueryBuilder;

$builder = new QueryBuilder\QueryBuilder\QueryBuilder();

$builder->select(["testField1", "testField2"], ["tf1", "tf2"]);
$builder->lineBreak();
$builder->from("testtable", "tt");
$builder->innerJoin("testtable2", "tt2", "tt1.id = tt2.testTable1_id");
$builder->where("tf1", "<=", "2");
$builder->orderBy("tf1, tf2");
print $builder->getQuery();