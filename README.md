# PHP Querybuilder
This package provides classes to create sql queries for different databases

## Supported
- MySQL
- PostgreSQL

## Planned
- SQLite



## Examples

### Mysql

```php
<?php

if(file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once  __DIR__ . '/vendor/autoload.php';
}
use M2\QueryBuilder\MySQLQueryBuilder;

$builder = new MySQLQueryBuilder();

$query = $builder->selectAll()->from("customer", "c")->where("rowid", ">=", "12345");
// query: SELECT * FROM customer AS c WHERE rowid >= 12345
```