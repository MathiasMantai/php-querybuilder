<?php

if(file_exists(__DIR__ . '/vendor/autoload.php'))
{
    require __DIR__ . '/vendor/autoload.php';
}

use M2\QueryBuilder\QueryBuilderFactory;
use M2\QueryBuilder\MySQLQueryBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class QueryBuilderFactoryTest extends TestCase
{
    public static function objectDataProvider(): array
    {
        return [
            [new MySQLQueryBuilder, QueryBuilderFactory::create("mysql")]
        ];
    }

    #[DataProvider("objectDataProvider")]
    public function testObjectFactory(MySQLQueryBuilder $expected, MySQLQueryBuilder $actual)
    {
        $this->assertObjectEquals($expected, $actual);
    }
}