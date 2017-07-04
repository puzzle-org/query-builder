<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\ValueObjects\Column;

class CountTest extends TestCase
{
    /**
     * @dataProvider providerTestCount
     */
    public function testCount($expected, $column, $alias)
    {
        $qb = new Count($column, $alias);

        $this->assertSame($qb->toString(), $expected);
    }

    public function providerTestCount()
    {
        return [
            ['COUNT(*)', new Wildcard(), null],
            ['COUNT(*) AS burger', new Wildcard(), 'burger'],
            ['COUNT(unicorn) AS burger', new Column('unicorn'), 'burger'],
            ['COUNT(z.unicorn) AS burger', new Column('z.unicorn'), 'burger'],
            ['COUNT(DISTINCT unicorn)', new Distinct(new Column('unicorn')), null],
            ['COUNT(DISTINCT unicorn) AS burger', new Distinct(new Column('unicorn')), 'burger'],
            ['COUNT(DISTINCT z.unicorn) AS burger', new Distinct(new Column('z.unicorn')), 'burger'],
        ];
    }
}
