<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

use Puzzle\QueryBuilder\Type;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Types\TString;
use Puzzle\QueryBuilder\Types\TInt;
use Puzzle\QueryBuilder\Types\TBool;
use Puzzle\QueryBuilder\Types\TDatetime;
use Puzzle\QueryBuilder\Types\TFloat;

class GreaterOrEqualOrEqualTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    /**
     * @dataProvider providerTestGreaterOrEqual
     */
    public function testGreaterOrEqual($expected, Type $column, $value)
    {
        $condition = new GreaterOrEqual($column, $value);

        $this->assertSame($expected, $condition->toString($this->escaper));
    }

    public function providerTestGreaterOrEqual()
    {
        return array(
            'null integer' => array("rank >= 0", new TInt('rank'), null),
            'integer zero' => array("rank >= 0", new TInt('rank'), 0),

            'integer' => array("rank >= 1337", new TInt('rank'), 1337),
            'integer in string' => array("rank >= 42", new TInt('rank'), '42'),

            'empty string' => array("score >= ''", new TString('score'), ''),
            'integer as string' => array("score >= '666'", new TString('score'), '666'),
            'string' => array("score >= 'unicorn'", new TString('score'), 'unicorn'),

            'simple datetime' => array("date >= '2014-03-07 14:18:42'", new TDatetime('date'), '2014-03-07 14:18:42'),
            'empty datetime' => array("date >= ''", new TDatetime('date'), ''),

            'float' => array("rank >= 13.37", new TFloat('rank'), 13.37),

            'empty column name' => array('', new TString(''), 'poney'),
        );
    }

    /**
     * @dataProvider providerTestFieldGreaterOrEqualThanField
     */
    public function testFieldGreaterOrEqualThanField($expected, $columnLeft, $columnRight)
    {
        $condition = new GreaterOrEqual($columnLeft, $columnRight);

        $this->assertSame($expected, $condition->toString($this->escaper));
    }

    public function providerTestFieldGreaterOrEqualThanField()
    {
        return array(
            array('pony >= unicorn', new TString('pony'), new TString('unicorn'),),
            array('pony >= id', new TString('pony'), new TInt('id'),),
            array('id >= pony', new TInt('id'), new TString('pony'),),
            array('id >= ponyId', new TInt('id'), new TInt('ponyId'),),
            array('creationDate >= updateDate', new TDatetime('creationDate'), new TDatetime('updateDate'),),
            array('good >= evil', new TBool('good'), new TBool('evil'),),
        );
    }
}