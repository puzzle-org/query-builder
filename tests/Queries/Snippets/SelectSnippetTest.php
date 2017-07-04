<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\ValueObjects\Column;
use Puzzle\QueryBuilder\Collections\SelectExpressionCollections\DuplicateAllowedSelectExpressionCollection;

class SelectSnippetTest extends TestCase
{
    /**
     * @dataProvider providerTestSelect
     */
    public function testSelect($expected, $expressions)
    {
        $select = new Select();
        $select->select($expressions);

        $this->assertSame($expected, $select->toString());
    }

    public function providerTestSelect()
    {
        return [
            'single expression' => [
                'SELECT name',
                new Column('name')
            ],
            'multiple expression' => [
                'SELECT name, color, age',
                new DuplicateAllowedSelectExpressionCollection([
                    new Column('name'),
                    new Column('color'),
                    new Column('age'),
                ])
            ],
            'duplicated expressions' => [
                'SELECT name, color, age',
                new DuplicateAllowedSelectExpressionCollection([
                    new Column('name'),
                    new Column('color'),
                    new Column('age'),
                    new Column('name'),
                ])
            ],
            'all expressions' => [
                'SELECT *',
                new Wildcard(),
            ],
        ];
    }

    public function testAddColumnsOnTheFly()
    {
        $select = new Select();
        $select
            ->select(new Column('id'))
            ->select(new Column('name'))
            ->select(new DuplicateAllowedSelectExpressionCollection([
                new Column('color'),
                new Column('taste')
            ]));

        $this->assertSame('SELECT id, name, color, taste', $select->toString());
    }
}