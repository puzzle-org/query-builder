<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use PHPUnit\Framework\TestCase;

class CountTest extends TestCase
{
    /**
     * @dataProvider providerTestCount
     */
    public function testCount($expected, $columnName, $alias)
    {
        $qb = new Count($columnName, $alias);

        $this->assertSame($qb->toString(), $expected);
    }

    public function providerTestCount()
    {
        return array(
            array('COUNT(*)', '*', null),
            array('COUNT(*) AS burger', '*', 'burger'),
            array('COUNT(unicorn) AS burger', 'unicorn', 'burger'),
            array('COUNT(z.unicorn) AS burger', 'z.unicorn', 'burger'),
            array('COUNT(DISTINCT unicorn)', new Distinct('unicorn'), null),
            array('COUNT(DISTINCT unicorn) AS burger', new Distinct('unicorn'), 'burger'),
            array('COUNT(DISTINCT z.unicorn) AS burger', new Distinct('z.unicorn'), 'burger'),
        );
    }

    /**
     * @dataProvider providerTestInvalidCount
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCount($columnName)
    {
        $qb = new Count($columnName);
    }

    public function providerTestInvalidCount()
    {
        return array(
            array(''),
            array(null),
        );
    }
}
