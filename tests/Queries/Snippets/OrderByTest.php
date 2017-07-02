<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use PHPUnit\Framework\TestCase;

class OrderByTest extends TestCase
{
    public function testDefaultOrderByDirection()
    {
        $qb = new OrderBy();
        $qb->addOrderBy('poney');

        $this->assertSame('ORDER BY poney ASC', $qb->toString());
    }

    /**
     * @dataProvider providerTestOrderBy
     */
    public function testOrderBy($expected, array $orderBy)
    {
        $qb = new OrderBy();

        foreach($orderBy as $column => $direction)
        {
            $qb->addOrderBy($column, $direction);
        }

        $this->assertSame($expected, $qb->toString());
    }

    public function providerTestOrderBy()
    {
        return array(
            'nominal ASC' => array('ORDER BY date ASC', ['date' => 'ASC']),
            'nominal DESC' => array('ORDER BY date DESC', ['date' => 'DESC']),

            'empty column name' => array('', ['' => 'DESC']),
            'null column name' => array('', [null => 'DESC']),

            'ASC only' => array('ORDER BY date ASC, id ASC', ['date' => 'ASC', 'id' => 'ASC']),
            'DESC only' => array('ORDER BY date DESC, id DESC', ['date' => 'DESC', 'id' => 'DESC']),
            'mixed direction' => array('ORDER BY date DESC, id ASC', ['date' => 'DESC', 'id' => 'ASC']),
            'empty column name' => array('ORDER BY date DESC, id ASC', ['date' => 'DESC', '' => 'ASC', 'id' => 'ASC']),
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnknownOrderByDirection()
    {
        $qb = new OrderBy();
        $qb->addOrderBy('poney', 'burger');
    }
}