<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\ValueObjects\Table;

class UpdateSnippetTest extends TestCase
{
    /**
     * @dataProvider providerTestUpdate
     */
    public function testUpdate($expected, $tableName, $alias)
    {
        $qb = new Update();
        $qb->addTable(new Table($tableName, $alias));

        $this->assertSame($qb->toString(), $expected);
    }

    public function providerTestUpdate()
    {
        return array(
            'String table name' => array('UPDATE burger', 'burger', null),
            'Mixed table name'  => array('UPDATE burger666', 'burger666', null),
            'wrapped with 0'    => array('UPDATE 000burger000', '000burger000', null),
            'empty alias'       => array('UPDATE burger', 'burger', ''),
            'with alias'        => array('UPDATE burger AS b', 'burger', 'b'),
        );
    }

    public function testUpdateUsingTableNamePart()
    {
        $qb = new Update();
        $qb->addTable(new Table('ponyz', 'p'));

        $this->assertSame($qb->toString(), 'UPDATE ponyz AS p');
    }

    public function testUpdateAddTable()
    {
        $qb = new Update();

        $qb
            ->addTable(new Table('ponyz', 'p'))
            ->addTable(new Table('burger', 'b'))
            ->addTable(new Table('unicorn', 'u'))
        ;

        $this->assertSame($qb->toString(), 'UPDATE ponyz AS p, burger AS b, unicorn AS u');
    }
}