<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries;

use Puzzle\QueryBuilder\Conditions;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use Puzzle\QueryBuilder\Queries\Snippets\OrderBy;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Types\TString;

class DeleteTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    private function newDelete(?string $table = null, ?string $alias = null): Delete
    {
        return (new Delete($table, $alias))->setEscaper($this->escaper);
    }

    public function testDeleteSingleTable()
    {
        $query = $this->newDelete();

        $query
            ->from('burger', 'b')
            ->where(new Conditions\Equal(new TString('type'), 'healthy'))
        ;

        $this->assertSame("DELETE FROM burger AS b WHERE type = 'healthy'", $query->toString($this->escaper));

        $query->orderBy('date', OrderBy::DESC);

        $this->assertSame("DELETE FROM burger AS b WHERE type = 'healthy' ORDER BY date DESC", $query->toString($this->escaper));

        $query
            ->limit(12)
            ->offset(5)
        ;

        $this->assertSame("DELETE FROM burger AS b WHERE type = 'healthy' ORDER BY date DESC LIMIT 12 OFFSET 5", $query->toString($this->escaper));
    }

    public function testDeleteWithInnerJoin()
    {
        $query = $this->newDelete();

        $query
            ->from('burger', 'b')
            ->where(new Conditions\Equal(new TString('type'), 'healthy'))
            ->innerJoin('taste', 't')->on('b.taste_id', 't.id')
        ;

        $this->assertSame("DELETE FROM burger AS b INNER JOIN taste AS t ON b.taste_id = t.id WHERE type = 'healthy'", $query->toString($this->escaper));
    }

    public function testDeleteWithLeftJoin()
    {
        $query = $this->newDelete();

        $query
            ->from('burger', 'b')
            ->where(new Conditions\Equal(new TString('type'), 'healthy'))
            ->leftJoin('taste', 't')->on('b.taste_id', 't.id')
        ;

        $this->assertSame("DELETE FROM burger AS b LEFT JOIN taste AS t ON b.taste_id = t.id WHERE type = 'healthy'", $query->toString($this->escaper));
    }

    public function testDeleteWithRightJoin()
    {
        $query = $this->newDelete();

        $query
            ->from('burger', 'b')
            ->where(new Conditions\Equal(new TString('type'), 'healthy'))
            ->rightJoin('taste', 't')->on('b.taste_id', 't.id')
        ;

        $this->assertSame("DELETE FROM burger AS b RIGHT JOIN taste AS t ON b.taste_id = t.id WHERE type = 'healthy'", $query->toString($this->escaper));
    }

    public function testDeleteUsingConstructor()
    {
        $query = $this->newDelete('burger', 'b');

        $query
            ->where(new Conditions\Equal(new TString('type'), 'healthy'))
        ;

        $this->assertSame("DELETE FROM burger AS b WHERE type = 'healthy'", $query->toString($this->escaper));
    }

    /**
     * @expectedException \LogicException
     */
    public function testDeleteWithoutFrom()
    {
        $query = $this->newDelete();

        $query->toString();
    }
}
