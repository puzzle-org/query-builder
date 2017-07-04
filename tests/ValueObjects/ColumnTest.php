<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\ValueObjects;

use Puzzle\QueryBuilder\Queries\Snippets\SelectExpression;
use Puzzle\QueryBuilder\Queries\Snippets\Count;
use PHPUnit\Framework\TestCase;

class ColumnTest extends TestCase
{
    /**
     * @dataProvider providerTestEquals
     */
    public function testEquals(SelectExpression $expr, bool $expected)
    {
        $column = new Column('pony');
        
        $this->assertSame($expected, $column->equals($expr));
    }
    
    public function providerTestEquals()
    {
        return [
            [new Column('pony'), true],
            [new Column(' pony '), false],
            [new Column('PONY'), false],
            [new Column('unicorn'), false],
                
            [new Count(new Column('toto')), false]
        ];
    }
}
