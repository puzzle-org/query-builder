<?php

use Puzzle\QueryBuilder\Queries;
use Puzzle\QueryBuilder\Conditions;
use Puzzle\QueryBuilder\Types;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use Puzzle\QueryBuilder\QueryPart;
use Puzzle\QueryBuilder\QueryPartAware;
use Puzzle\QueryBuilder\Conditions\Sets\AndSet;
use PHPUnit\Framework\TestCase;

class IsCute implements QueryPart
{
    public function build(QueryPartAware $query): void
    {
        $conditionSet = new AndSet();
        $conditionSet
            ->add(new Conditions\Equal(new Types\TString('color'), 'white'))
            ->add(new Conditions\Lower(new Types\TInt('age'), 1));

        $query->where($conditionSet);
    }
}

class IsPony implements QueryPart
{
    public function build(QueryPartAware $query): void
    {
        $query
            ->needTable('creatures')
            ->where(new Conditions\Equal(new Types\TString('type'), 'pony'));
    }
}

class QueryPartTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    public function testSelectQueryPart()
    {
        $query = (new Queries\Select())->setEscaper($this->escaper);
        $query
            ->select([ 'name', 'color' ])
            ->from('creatures')
            ->add(new IsPony())
            ->add(new IsCute())
            ->where(new Conditions\Equal(new Types\TString('owner'), 'Paul'))
            ->having(new Conditions\Greater(new Types\TInt('rank'), 42));

        $this->assertSame("SELECT name, color FROM creatures WHERE type = 'pony' AND color = 'white' AND age < 1 AND owner = 'Paul' HAVING rank > 42", $query->toString());
    }

    public function testUpdateWithQueryPart()
    {
        $query = (new Queries\Update())->setEscaper($this->escaper);

        $query
            ->update('poney')
            ->set(array('owner' => 'John'))
            ->where(new Conditions\In(new Types\TString('author'), array('julian', 'claude')))
            ->add(new IsCute());

        $this->assertSame("UPDATE poney SET owner = 'John' WHERE author IN ('julian', 'claude') AND color = 'white' AND age < 1", $query->toString($this->escaper));
    }

    public function testDeleteWithQueryPart()
    {
        $query = (new Queries\Delete('burger', 'b'))->setEscaper($this->escaper);

        $query
            ->where(new Conditions\Equal(new Types\TString('owner'), 'Claude'))
            ->add(new IsCute());

        $this->assertSame("DELETE FROM burger AS b WHERE owner = 'Claude' AND color = 'white' AND age < 1", $query->toString($this->escaper));
    }

    public function testNeededTableIsPresentInFromSnippet()
    {
        $query = (new Queries\Select())->setEscaper($this->escaper);
        $query
            ->select([ 'name', 'color' ])
            ->from('creatures')
            ->add(new IsPony());

        $query->toString();

        $this->assertTrue(true);
    }

    public function testNeededTableIsPresentInJoinSnippet()
    {
        $query = (new Queries\Select())->setEscaper($this->escaper);
        $query
            ->select([ 'name', 'color' ])
            ->from('burger')
            ->innerJoin('creatures')->on('id_burger', 'id_creature')
            ->add(new IsPony());

        $query->toString();

        $this->assertTrue(true);
    }

    public function testNeededTableWithAliases()
    {
        $query = (new Queries\Select())->setEscaper($this->escaper);
        $query
            ->needTable('b')
            ->needTable('p')
            ->select([ 'name', 'color' ])
            ->from('burger', 'b')
            ->leftJoin('pony', 'p')->on('id_burger', 'id_pony');

        $query->toString();

        $this->assertTrue(true);
    }

    /**
     * @expectedException \LogicException
     */
    public function testMissingNeededTable()
    {
        $query = (new Queries\Select())->setEscaper($this->escaper);
        $query
            ->select([ 'name', 'color' ])
            ->from('burger')
            ->add(new IsPony());

        $query->toString();
    }

    public function testUpdateWithNeededTable()
    {
        $query = (new Queries\Update())->setEscaper($this->escaper);

        $query
            ->update('creatures')
            ->set(array('owner' => 'John'))
            ->add(new IsPony());

        $query->toString();

        $this->assertTrue(true);
    }

    /**
     * @expectedException \LogicException
     */
    public function testUpdateWithMissingNeededTable()
    {
        $query = (new Queries\Update())->setEscaper($this->escaper);

        $query
            ->update('animals')
            ->set(array('owner' => 'John'))
            ->add(new IsPony());

        $query->toString();
    }

    public function testDeleteWithNeededTable()
    {
        $query = (new Queries\Delete('creatures'))->setEscaper($this->escaper);

        $query
            ->where(new Conditions\Equal(new Types\TString('owner'), 'Claude'))
            ->add(new IsPony());

        $query->toString();

        $this->assertTrue(true);
    }

    /**
     * @expectedException \LogicException
     */
    public function testDeleteWithMissingNeededTable()
    {
        $query = (new Queries\Delete('burger'))->setEscaper($this->escaper);

        $query
            ->where(new Conditions\Equal(new Types\TString('owner'), 'Claude'))
            ->add(new IsPony());

        $query->toString();
    }
}
