<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries;

use Puzzle\QueryBuilder\Conditions;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use Puzzle\QueryBuilder\Queries\Snippets\OrderBy;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Types\TInt;
use Puzzle\QueryBuilder\Types\TString;

class UpdateTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    private function newUpdate(?string $table = null, ?string $alias = null): Update
    {
        return (new Update($table, $alias))->setEscaper($this->escaper);
    }

    public function testUpdateUsingConstructor()
    {
        $query = $this->newUpdate('burger');

        $query
            ->set(array('taste' => 'cheese'))
            ->set(array('vegan' => false))
            ->set(array('name' => 'The big one'))
        ;

        $this->assertSame("UPDATE burger SET taste = 'cheese', vegan = 0, name = 'The big one'", $query->toString($this->escaper));

        $query
            ->where(new Conditions\Greater(new TInt('score'), 1337))
            ->where(new Conditions\Equal(new TString('author'), 'julian'))
        ;

        $this->assertSame("UPDATE burger SET taste = 'cheese', vegan = 0, name = 'The big one' WHERE score > 1337 AND author = 'julian'", $query->toString($this->escaper));

        $query->orderBy('id', OrderBy::DESC);

        $this->assertSame("UPDATE burger SET taste = 'cheese', vegan = 0, name = 'The big one' WHERE score > 1337 AND author = 'julian' ORDER BY id DESC", $query->toString($this->escaper));

        $query->limit(12);

        $this->assertSame("UPDATE burger SET taste = 'cheese', vegan = 0, name = 'The big one' WHERE score > 1337 AND author = 'julian' ORDER BY id DESC LIMIT 12", $query->toString($this->escaper));
    }

    public function testSimpleUpdateUsingSetter()
    {
        $query = $this->newUpdate();

        $query
            ->update('burger')
            ->set(array('date' => '2014-03-07 13:37:42'))
        ;

        $this->assertSame("UPDATE burger SET date = '2014-03-07 13:37:42'", $query->toString($this->escaper));
    }

    public function testUpdateWithJoin()
    {
        $query = $this->newUpdate('burger', 'b');

        $query
            ->innerjoin('taste', 't')->on('b.taste_id', 't.id')
            ->set(array('date' => '2014-03-07 13:37:42'))
        ;

        $this->assertSame("UPDATE burger AS b INNER JOIN taste AS t ON b.taste_id = t.id SET date = '2014-03-07 13:37:42'", $query->toString($this->escaper));

        $query = $this->newUpdate('burger', 'b');

        $query
            ->leftJoin('taste', 't')->on('b.taste_id', 't.id')
            ->set(array('date' => '2014-03-07 13:37:42'))
        ;

        $this->assertSame("UPDATE burger AS b LEFT JOIN taste AS t ON b.taste_id = t.id SET date = '2014-03-07 13:37:42'", $query->toString($this->escaper));

        $query = $this->newUpdate('burger', 'b');

        $query
            ->rightJoin('taste', 't')->on('b.taste_id', 't.id')
            ->set(array('date' => '2014-03-07 13:37:42'))
        ;

        $this->assertSame("UPDATE burger AS b RIGHT JOIN taste AS t ON b.taste_id = t.id SET date = '2014-03-07 13:37:42'", $query->toString($this->escaper));
    }

    public function testUpdateMultipleTable()
    {
        $query = $this->newUpdate();

        $query
            ->update('burger', 'b')
            ->update('poney', 'p')
            ->set(array('date' => '2014-03-07 13:37:42'))
            ->where(new Conditions\In(new TString('author'), array('julian', 'claude')))
        ;

        $this->assertSame("UPDATE burger AS b, poney AS p SET date = '2014-03-07 13:37:42' WHERE author IN ('julian', 'claude')", $query->toString($this->escaper));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testUpdateWithoutTable()
    {
        $query = $this->newUpdate();

        $query
            ->set(array('date' => '2014-03-07 13:37:42'))
        ;

        $query->toString($this->escaper);
    }
}
