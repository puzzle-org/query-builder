<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\ValueObjects\Column;

class DistinctTest extends TestCase
{
    /**
     * @dataProvider providerTestDistinct
     */
    public function testDistinct($expected, $columnName)
    {
        $qb = new Distinct(new Column($columnName));

        $this->assertSame($qb->toString(), $expected);
    }

    public function providerTestDistinct()
    {
        return array(
            array('DISTINCT id', 'id'),
        );
    }
}
