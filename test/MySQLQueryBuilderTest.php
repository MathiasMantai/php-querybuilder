<?php

if(file_exists(__DIR__ . '/vendor/autoload.php'))
{
    require __DIR__ . '/vendor/autoload.php';
}

use Mmantai\QueryBuilder\MySQLQueryBuilder;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class MySQLQueryBuilderTest extends TestCase
{
    /**
     * Test all elemental methods
     * @return void
     */
    public function testElemental(): void
    {
        $builder = new MySQLQueryBuilder();

        $this->assertSame("SELECT rowid, name, description", $builder->select(["rowid", "name", "description"])->get());

        $this->assertSame("SELECT *", $builder->selectAll()->get());
        $this->assertSame("SELECT field1 AS f1, field2, field3 AS f3", $builder->select([["field1", "f1"], "field2", ["field3", "f3"]])->get());

        $this->assertSame(" FROM table1 AS t1", $builder->from("table1", "t1")->get());
        $this->assertSame(" FROM table2", $builder->from("table2")->get());

        $this->assertSame(" INNER JOIN table3 AS t3", $builder->innerJoin("table3", "t3")->get());
        $this->assertSame(" INNER JOIN table4", $builder->innerJoin("table4")->get());

        $this->assertSame(" LEFT JOIN table5 AS t5", $builder->leftJoin("table5", "t5")->get());
        $this->assertSame(" LEFT JOIN table5 AS t5", $builder->leftJoin("table5", "t5")->get());

        $this->assertSame(" RIGHT JOIN table6 AS t6", $builder->rightJoin("table6", "t6")->get());
        $this->assertSame(" RIGHT JOIN table7", $builder->rightJoin("table7")->get());

        $this->assertSame(" FULL JOIN table8 AS t8", $builder->fullJoin("table8", "t8")->get());
        $this->assertSame(" FULL JOIN table9", $builder->fullJoin("table9")->get());

        $this->assertSame(" NATURAL JOIN table10 AS t10", $builder->naturalJoin("table10", "t10")->get());
        $this->assertSame(" NATURAL JOIN table11", $builder->naturalJoin("table11")->get());

        $this->assertSame(" NATURAL LEFT JOIN table12 AS t12", $builder->naturalJoin("table12", "t12", "left")->get());
        $this->assertSame(" NATURAL RIGHT JOIN table12 AS t12", $builder->naturalJoin("table12", "t12", "right")->get());
        $this->assertSame(" NATURAL INNER JOIN table12 AS t12", $builder->naturalJoin("table12", "t12", "inner")->get());

        $this->assertSame(" ON t3.spalte = t4.spalte", $builder->on("t3.spalte", "t4.spalte")->get());

        $this->assertSame(" WHERE t3.spalte > 5", $builder->where("t3.spalte", ">", 5)->get());
        $this->assertSame(" WHERE t4.spalte = 'test'", $builder->where("t4.spalte", "=", "test")->get());

        $this->assertSame(" AND t4.spalte < 4", $builder->and("t4.spalte", "<", 4)->get());
        $this->assertSame(" AND t4.spalte != 'test2'", $builder->and("t4.spalte", "!=", "test2")->get());
        $this->assertSame(" AND t4.spalte != false", $builder->and("t4.spalte", "!=", "false")->get());
  

        $this->assertSame(" OR t5.spalte != 'THIS IS AND'", $builder->or("t5.spalte", "!=", "THIS IS AND")->get());

        $this->assertSame(" ORDER BY spalte1, spalte2 DESC, spalte3 ASC", $builder->orderBy(["spalte1", "spalte2", "spalte3"], ["", "DESC", "ASC"])->get());

        $this->assertSame(" GROUP BY spalte1, spalte2, spalte3", $builder->groupBy(["spalte1", "spalte2", "spalte3"])->get());

        $this->assertSame(" UNION SELECT * FROM test2", $builder->union("test2")->get());

        $this->assertSame(" UNION ALL SELECT * FROM test2", $builder->unionAll("test2")->get());

        $this->assertSame("SELECT * FROM table1 INTERSECT SELECT * FROM table2", $builder->selectAll()->from("table1")->intersect()->selectAll()->from("table2")->get());
    }

    public static function selectDataProvider(): array
    {
        $builder = new MySQLQueryBuilder();
        return [
            [
                "SELECT * FROM tabelle1 AS t1 WHERE spalte >= 100",
                $builder->selectAll()->from("tabelle1", "t1")->where("spalte", ">=", 100)->get()
            ],
            [
                "SELECT rowid, name, description, email FROM customer WHERE rowid > 10599",
                $builder->select(["rowid", "name", "description", "email"])->from("customer")->where("rowid", ">", 10599)->get()
            ]
        ];
    }

    #[DataProvider("selectDataProvider")]
    public function testSelectQueries(string $expected, string $actual): void
    {
        $this->assertSame($expected, $actual);
    }


    public static function updateDataProvider(): array
    {
        $builder = new MySQLQueryBuilder();

        return [
            [
                "UPDATE tabelle SET spalte = 'Testspalte' WHERE rowid = 123456",
                $builder->update("tabelle")->set("spalte", "Testspalte")->where("rowid", "=", 123456)->get()
            ],
            [
                "UPDATE tabelle SET name = 'Update', bla = 'Hallo Welt' WHERE rowid > 5",
                $builder->update("tabelle")->setMulti(["name", "bla"], ['Update', 'Hallo Welt'])->where("rowid", ">", 5)->get()
            ]
        ];
    }

    #[DataProvider("updateDataProvider")]
    public function testUpdateQueries(string $expected, string $actual): void
    {
        $this->assertSame($expected, $actual);
    }

    public static function deleteDataProvider(): array
    {
        $builder = new MySQLQueryBuilder();

        return [
            [
                "DELETE FROM tabelle WHERE rowid != 5 AND name LIKE '%dummy%'",
                $builder->delete("tabelle")->where("rowid", "!=", 5)->and("name", "LIKE", "%dummy%")->get()
            ],
            [
                "UPDATE tabelle SET name = 'Update', bla = 'Hallo Welt' WHERE rowid > 5",
                $builder->update("tabelle")->setMulti(["name", "bla"], ['Update', 'Hallo Welt'])->where("rowid", ">", 5)->get()
            ]
        ];
    }

    #[DataProvider("deleteDataProvider")]
    public function testDeleteQueries(string $expected, string $actual): void
    {
        $this->assertSame($expected, $actual);
    }


    //INSERT INTO
    public static function insertDataProvider(): array
    {
        $builder = new MySQLQueryBuilder();

        return [
            [
                "INSERT INTO tabelle (name, description, updated) VALUES ('test', 'This is an insert test', '')",
                $builder->insert("tabelle", ["name", "description", "updated"], ['test', 'This is an insert test', ''])->get()
            ],
            [
                "INSERT INTO t (rowid, name, ref) VALUES (58879, 'Testname', 'AB8600058')",
                $builder->insert("t", ["rowid", "name", "ref"], [58879, "Testname", "AB8600058"])->get()
            ]
        ];
    }

    #[DataProvider("insertDataProvider")]
    public function testInsertQueries(string $expected, string $actual): void
    {
        $this->assertSame($expected, $actual);
    }
}