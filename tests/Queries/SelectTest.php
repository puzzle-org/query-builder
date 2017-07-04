<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries;

use Puzzle\QueryBuilder\Conditions;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Types\TString;
use Puzzle\QueryBuilder\Types\TInt;
use Puzzle\QueryBuilder\ValueObjects\Column;

class SelectTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    private function newSelect(): Select
    {
        return (new Select())->setEscaper($this->escaper);
    }

    public function testSelect()
    {
        $query = $this->newSelect();

        $query
            ->select(array('id', 'name'))
            ->from('poney')
            ->where(new Conditions\Equal(new TString('name'), 'burger'))
        ;

        $this->assertSame("SELECT id, name FROM poney WHERE name = 'burger'", $query->toString($this->escaper));
    }

    public function testSelectAlias()
    {
        $query = $this->newSelect();

        $query
            ->select(array('id', 'name'))
            ->from('poney', 'z')
        ;

        $this->assertSame("SELECT id, name FROM poney AS z", $query->toString($this->escaper));
    }

    public function testSelectMultipleWhere()
    {
        $query = $this->newSelect();

        $query
            ->select(array('id', 'name'))
            ->from('poney')
            ->where(new Conditions\Equal(new TString('name'), 'burger'))
            ->where(new Conditions\Equal(new TInt('rank'), 42))
        ;

        $this->assertSame("SELECT id, name FROM poney WHERE name = 'burger' AND rank = 42", $query->toString($this->escaper));
    }

    public function testSelectMultipleSelect()
    {
        $query = $this->newSelect();

        $query
            ->select(array('id', 'name'))
            ->select('rank')
            ->select(array('taste', 'price'))
            ->select('rank')
            ->from('poney')
            ->where(new Conditions\Equal(new TString('name'), 'burger'))
        ;

        $this->assertSame("SELECT id, name, rank, taste, price FROM poney WHERE name = 'burger'", $query->toString($this->escaper));
    }

    /**
     * @expectedException \LogicException
     */
    public function testSelectWithoutSelectOrFrom()
    {
        $query = $this->newSelect();

        $query->toString();
    }

    /**
     * @expectedException \LogicException
     */
    public function testSelectWithoutFrom()
    {
        $query = (new Select())->setEscaper($this->escaper)
            ->select('burger');

        $query->toString();
    }

    /**
     * @expectedException \LogicException
     */
    public function testSelectWithoutSelectingColumns()
    {
        $query = $this->newSelect();
        $query->from('poney');

        $query->toString();
    }

    /**
     * @expectedException \LogicException
     */
    public function testJoinWrongSynthax()
    {
        $query = $this->newSelect();

        $query
            ->select('id')
            ->from('truc')
            ->on('p.taste_id', 't.id')
        ;

        $query->toString();
    }

    public function testSingleRightJoin()
    {
        $query = $this->newSelect();

        $query
            ->select('id')
            ->from('poney', 'p')
            ->rightJoin('taste', 't')->on('p.taste_id', 't.id')
            ->where(new Conditions\Equal(new TString('name'), 'burger'))
        ;

        $this->assertSame("SELECT id FROM poney AS p RIGHT JOIN taste AS t ON p.taste_id = t.id WHERE name = 'burger'", $query->toString($this->escaper));
    }

    public function testSingleLeftJoin()
    {
        $query = $this->newSelect();

        $query
            ->select('id')
            ->from('poney', 'p')
            ->leftJoin('taste', 't')->on('p.taste_id', 't.id')
            ->where(new Conditions\Equal(new TString('name'), 'burger'))
        ;

        $this->assertSame("SELECT id FROM poney AS p LEFT JOIN taste AS t ON p.taste_id = t.id WHERE name = 'burger'", $query->toString($this->escaper));
    }

    public function testSingleInnerJoin()
    {
        $query = $this->newSelect();

        $query
            ->select('id')
            ->from('poney', 'p')
            ->innerJoin('taste', 't')->on('p.taste_id', 't.id')
            ->where(new Conditions\Equal(new TString('name'), 'burger'))
        ;

        $this->assertSame("SELECT id FROM poney AS p INNER JOIN taste AS t ON p.taste_id = t.id WHERE name = 'burger'", $query->toString($this->escaper));
    }

    public function testMultipleInnerJoin()
    {
        $query = $this->newSelect();

        $query
            ->select('id')
            ->from('poney', 'p')
            ->innerJoin('taste', 't')->on('p.taste_id', 't.id')
            ->innerJoin('country', 'c')->on('c.country_id', 'c.id')
            ->innerJoin('unicorn')->using('code')
            ->where(new Conditions\Equal(new TString('name'), 'burger'))
        ;

        $this->assertSame("SELECT id FROM poney AS p INNER JOIN taste AS t ON p.taste_id = t.id INNER JOIN country AS c ON c.country_id = c.id INNER JOIN unicorn USING (code) WHERE name = 'burger'", $query->toString($this->escaper));
    }

    public function testMultipleMixedJoin()
    {
        $query = $this->newSelect();

        $query
            ->select('id')
            ->from('poney', 'p')
            ->innerJoin('taste', 't')->on('p.taste_id', 't.id')
            ->rightJoin('country', 'c')->on('c.country_id', 'c.id')
            ->leftJoin('unicorn')->using('code')
            ->where(new Conditions\Equal(new TString('name'), 'burger'))
        ;

        $this->assertSame("SELECT id FROM poney AS p INNER JOIN taste AS t ON p.taste_id = t.id RIGHT JOIN country AS c ON c.country_id = c.id LEFT JOIN unicorn USING (code) WHERE name = 'burger'", $query->toString($this->escaper));
    }

    public function testLimit()
    {
        $query = $this->newSelect();

        $query
            ->select('id')
            ->from('poney', 'p')
            ->where(new Conditions\Equal(new TString('name'), 'burger'))
            ->limit(42)
        ;

        $this->assertSame("SELECT id FROM poney AS p WHERE name = 'burger' LIMIT 42", $query->toString($this->escaper));
    }

    public function testGroupBy()
    {
        $query = $this->newSelect();

        $query
            ->select('id')
            ->from('poney', 'p')
            ->where(new Conditions\Equal(new TString('name'), 'burger'))
            ->groupBy('taste_id')
            ->groupBy('color')
            ->limit(42)
            ->offset(1337)
            ->orderBy('poney')
        ;

        $this->assertSame("SELECT id FROM poney AS p WHERE name = 'burger' GROUP BY taste_id, color ORDER BY poney ASC LIMIT 42 OFFSET 1337", $query->toString($this->escaper));
    }

    public function testOrderBy()
    {
        $query = $this->newSelect();

        $query
            ->select('id')
            ->from('poney', 'p')
            ->where(new Conditions\Equal(new TString('name'), 'burger'))
            ->limit(42)
            ->offset(1337)
            ->orderBy('poney')
        ;

        $this->assertSame("SELECT id FROM poney AS p WHERE name = 'burger' ORDER BY poney ASC LIMIT 42 OFFSET 1337", $query->toString($this->escaper));
    }

    public function testLimitWithOffset()
    {
        $query = $this->newSelect();

        $query
            ->select('id')
            ->from('poney', 'p')
            ->where(new Conditions\Equal(new TString('name'), 'burger'))
            ->limit(42)
            ->offset(1337)
        ;

        $this->assertSame("SELECT id FROM poney AS p WHERE name = 'burger' LIMIT 42 OFFSET 1337", $query->toString($this->escaper));
    }

    /**
     * @expectedException \LogicException
     */
    public function testOffsetWithoutLimit()
    {
        $query = $this->newSelect();

        $query
            ->select('id')
            ->from('poney', 'p')
            ->where(new Conditions\Equal(new TString('name'), 'burger'))
            ->offset(666)
        ;

        $query->toString($this->escaper);
    }

    public function testOffsetWithEmptyLimit()
    {
        $query = $this->newSelect();

        $query
            ->select('id')
            ->from('poney', 'p')
            ->where(new Conditions\Equal(new TString('name'), 'burger'))
            ->limit('')
            ->offset(666)
        ;

        $this->assertSame("SELECT id FROM poney AS p WHERE name = 'burger'", $query->toString($this->escaper));
    }

    public function testHaving()
    {
        $query = $this->newSelect();

        $query
            ->select('id')
            ->from('poney', 'p')
            ->having(new Conditions\Greater(new TInt('rank'), 42))
        ;

        $this->assertSame("SELECT id FROM poney AS p HAVING rank > 42", $query->toString($this->escaper));

        $query->having(
            (new Conditions\Equal(new TInt('author_id'), 12))->or(new Conditions\Equal(new TInt('author_id'), 666))
        );

        $this->assertSame("SELECT id FROM poney AS p HAVING (rank > 42) AND (author_id = 12 OR author_id = 666)", $query->toString($this->escaper));
    }

    public function testSelectCount()
    {
        $query = $this->newSelect();
        $query
            ->select(array(
                new Snippets\Count(new Snippets\Distinct(new Column('votes'))),
                'id',
                'name'
            ))
            ->from('poney')
            ->where(new Conditions\Equal(new TString('name'), 'burger'))
        ;

        $this->assertSame("SELECT COUNT(DISTINCT votes), id, name FROM poney WHERE name = 'burger'", $query->toString($this->escaper));
    }
}