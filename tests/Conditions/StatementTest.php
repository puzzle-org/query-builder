<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

use Puzzle\QueryBuilder\Conditions;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use Puzzle\QueryBuilder\Queries;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Types\TString;

class StatementTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    /**
     * @dataProvider providerTestStatement
     */
    public function testStatement($expected, $statement)
    {
        $condition = new Statement($statement);

        $this->assertSame($expected, $condition->toString($this->escaper));
    }

    public function providerTestStatement()
    {
        $condition = new Different(new TString('taste'), 'vegetable');
        $subQuery = (new Queries\Select('*'))->from('burger')->where($condition);

        return array(
            'string' => array("lorem ipsum", "lorem ipsum"),
            'null' => array('', null),
            'empty string' => array('', ''),
            'sub query' => array("(SELECT * FROM burger WHERE taste != 'vegetable')", $subQuery),
        );
    }

    public function testCompositeStatement()
    {
        $condition = new Different(new TString('taste'), 'vegetable');
        $statementCondition = new Statement('Spread ponyz over the world');
        $subQuery = new Statement((new Queries\Select('*'))->from('burger')->where($condition));

        $compositeCondition = $condition->and($statementCondition)->or($subQuery);

        $this->assertSame("(taste != 'vegetable' AND Spread ponyz over the world) OR (SELECT * FROM burger WHERE taste != 'vegetable')", $compositeCondition->toString($this->escaper));

        $compositeCondition = $condition->and($subQuery->or($statementCondition));

        $this->assertSame("taste != 'vegetable' AND ((SELECT * FROM burger WHERE taste != 'vegetable') OR Spread ponyz over the world)", $compositeCondition->toString($this->escaper));
    }

    /**
     * @dataProvider providerTestIsEmpty
     */
    public function testIsEmpty($expected, $statement)
    {
        $condition = new Statement($statement);

        $this->assertSame($expected, $condition->isEmpty());
    }

    public function providerTestIsEmpty()
    {
        $condition = new Different(new TString('taste'), 'vegetable');
        $subQuery = new Statement((new Queries\Select('*'))->from('burger')->where($condition));

        return array(
            'string' => array(false, 'ponyz over burgerz'),
            'int' => array(false, 42),
            'empty string' => array(true, ''),
            'null' => array(true, null),
            'sub query' => array(false, $subQuery),
        );
    }
}