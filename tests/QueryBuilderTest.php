<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder;

use Puzzle\QueryBuilder\QueryBuilder;
use Puzzle\QueryBuilder\Queries;
use Puzzle\QueryBuilder\Conditions;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use Puzzle\QueryBuilder\Queries\Snippets\Distinct;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Traits\EscaperAware;
use Puzzle\QueryBuilder\Types\TString;
use Puzzle\QueryBuilder\ValueObjects\Column;

class QueryBuilderTest extends TestCase
{
    use EscaperAware;

    protected function setUp()
    {
        $this->setEscaper(new AlwaysQuoteEscaper());
    }

    private function newQueryBuilder()
    {
        return (new QueryBuilder())->setEscaper($this->escaper);
    }

    public function testDelete()
    {
        $qb = $this->newQueryBuilder();

        $condition = new Conditions\Different(new TString('taste'), 'burger');

        $query = $qb->delete('poney')->where($condition);

        $delete = new Queries\Delete('poney');
        $delete->setEscaper($this->escaper);
        $delete->where($condition);

        $this->assertSame($delete->toString(), $query->toString());
    }

    public function testInsert()
    {
        $qb = $this->newQueryBuilder();

        $values = array(
            'id' => 42,
            'name' => 'julian',
            'taste' => 'burger',
        );

        $query = $qb->insert('poney')->values($values);

        $insert = new Queries\Insert('poney');
        $insert->setEscaper($this->escaper);
        $insert->values($values);

        $this->assertSame($insert->toString(), $query->toString());
    }

    public function testUpdate()
    {
        $qb = $this->newQueryBuilder();

        $fields = array('taste' => 'burger');

        $query = $qb->update('poney')->set($fields);

        $update = new Queries\Update('poney');
        $update->setEscaper($this->escaper);
        $update->set($fields);

        $this->assertSame($update->toString(), $query->toString());
    }

    public function testSelect()
    {
        $qb = $this->newQueryBuilder();

        $query = $qb
            ->select(array(
                $qb->count($qb->distinct('id'), 'total'),
                'b.type'
            ))
            ->from('burger', 'b');

        $select = new Queries\Select(array(
            new Queries\Snippets\Count(
                new Queries\Snippets\Distinct(
                    new Column('id')
                ),
                'total'
            ),
            'b.type',
        ));
        $select->setEscaper($this->escaper);
        $select->from('burger', 'b');

        $this->assertSame($select->toString(), $query->toString());
    }
}
