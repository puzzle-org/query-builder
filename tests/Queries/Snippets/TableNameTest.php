<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use PHPUnit\Framework\TestCase;

class TableNameTest extends TestCase
{
    /**
     * @dataProvider providerTestTableName
     */
    public function testTableName($expected, $tableName, $alias)
    {
        $qb = new TableName($tableName, $alias);

        $this->assertSame($qb->toString(), $expected);
    }

    public function providerTestTableName()
    {
        return array(
            'nominal' => array('poney AS p', 'poney', 'p'),
            'alias is table name' => array('poney AS poney', 'poney', 'poney'),
            'empty alias' => array('poney', 'poney', ''),
            'null alias' => array('poney', 'poney', null),
        );
    }

    /**
     * @dataProvider providerTestEmptyTableName
     * @expectedException \InvalidArgumentException
     */
    public function testEmptyTableName($tableName)
    {
        $qb = new TableName($tableName);

        $qb->toString($tableName);
    }

    public function providerTestEmptyTableName()
    {
        return array(
            'empty table name' => array(''),
            'null table name' => array(null),
        );
    }
}