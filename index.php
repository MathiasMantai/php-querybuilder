<?php

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}


use Classes\QueryBuilder;

$builder = new QueryBuilder();

$builder->select(["testField1", "testField2"], ["tf1", "tf2"]);
$builder->from("testtable", "tt");
$builder->innerJoin("testtable2", "tt2", "tt1.id = tt2.testTable2_id");
$builder->rightJoin("testtable3", null, "testtable3.id = tt2.testTable2_id");
$builder->where("tf1", "<=", "2");
$builder->orderBy("tf1, tf2");
print $builder->getQuery();