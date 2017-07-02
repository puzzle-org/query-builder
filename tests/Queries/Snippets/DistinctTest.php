<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use PHPUnit\Framework\TestCase;

class DistinctTest extends TestCase
{
    /**
     * @dataProvider providerTestDistinct
     */
    public function testDistinct($expected, $columnName)
    {
        $qb = new Distinct($columnName);

        $this->assertSame($qb->toString(), $expected);
    }

    public function providerTestDistinct()
    {
        return array(
            array('DISTINCT id', 'id'),
        );
    }

    /**
     * @dataProvider providerTestInvalidDistinct
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidDistinct($columnName)
    {
        $qb = new Distinct($columnName);
    }

    public function providerTestInvalidDistinct()
    {
        return array(
            array(''),
            array(null),
        );
    }
}
