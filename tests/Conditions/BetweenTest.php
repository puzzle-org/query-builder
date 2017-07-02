<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

use Puzzle\QueryBuilder\Type;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Types\TInt;
use Puzzle\QueryBuilder\Types\TString;
use Puzzle\QueryBuilder\Types\TDatetime;

class BetweenTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    /**
     * @dataProvider providerTestBetween
     */
    public function testBetween($expected, Type $column, $start, $end)
    {
        $condition = new Between($column, $start, $end);

        $this->assertSame($expected, $condition->toString($this->escaper));
    }

    public function providerTestBetween()
    {
        return array(
            'simple string' => array("id BETWEEN 'A' AND 'F'", new TString('id'), 'A', 'F'),

            'simple int' => array("id BETWEEN 42 AND 666", new TInt('id'), 42, 666),
            'start value empty' => array('', new TInt('id'), null, 666),
            'end value empty' => array('', new TInt('id'), 42, null),

            'string datetime' => array("date BETWEEN '2014-12-01 13:37:42' AND '2014-12-17 14:18:69'", new TDatetime('date'), '2014-12-01 13:37:42', '2014-12-17 14:18:69'),
            'datetime' => array(
                "date BETWEEN '2014-12-01 13:37:42' AND '2014-12-17 14:18:09'",
                new TDatetime('date'),
                \Datetime::createFromFormat('Y-m-d H:i:s', '2014-12-01 13:37:42'),
                \Datetime::createFromFormat('Y-m-d H:i:s', '2014-12-17 14:18:09')
            ),
        );
    }

    /**
     * @dataProvider providerTestIsEmpty
     */
    public function testIsEmpty($expected, Type $column, $start, $end)
    {
        $condition = new Between($column, $start, $end);

        $this->assertSame($expected, $condition->isEmpty());
    }

    public function providerTestIsEmpty()
    {
        return array(
            'simple string' => array(false, new TString('id'), 'A', 'F'),

            'all empty' => array(true, new TInt('id'), '', ''),
            'start string empty' => array(true, new TString('id'), 'A', ''),
            'end string empty' => array(true, new TString('id'), '', 'F'),

            'all null' => array(true, new TInt('id'), null, null),
            'start value null' => array(true, new TInt('id'), null, 666),
            'end value null' => array(true, new TInt('id'), 42, null),

            'simple int' => array(false, new TInt('id'), 42, 666),
        );
    }
}
