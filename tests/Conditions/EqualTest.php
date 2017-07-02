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

class EqualTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    /**
     * @dataProvider providerTestEqual
     */
    public function testEqual($expected, Type $column, $value)
    {
        $condition = new Equal($column, $value);

        $this->assertSame($expected, $condition->toString($this->escaper));
    }

    public function providerTestEqual()
    {
        return array(
            'simple string' => array("name = 'poney'", new TString('name'), 'poney'),
            'empty string'  => array("name = ''", new TString('name'), ''),

            'simple int'    => array("id = 666", new TInt('id'), 666),
            'empty int'     => array('id = 0', new TInt('id'), ''),

            'simple datetime'    => array("date = '2014-03-07 14:18:42'", new TDatetime('date'), '2014-03-07 14:18:42'),
            'empty datetime'     => array("date = ''", new TDatetime('date'), ''),

            'float'    => array("rank = 13.37", new TFloat('rank'), 13.37),

            'empty column name' => array('', new TString(''), 'poney'),
        );
    }

    /**
     * @dataProvider providerTestIsEmpty
     */
    public function testIsEmpty($expected, Type $column, $value)
    {
        $condition = new Equal($column, $value);

        $this->assertSame($expected, $condition->isEmpty());
    }

    public function providerTestIsEmpty()
    {
        return array(
            'simple string' => array(false, new TString('name'), 'poney'),
            'empty string' => array(false, new TString('name'), ''),

            'simple int' => array(false, new TInt('id'), 42),
            'empty int' => array(false, new TInt('id'), ''),

            'simple datetime' => array(false, new TDatetime('date'), '2014-12-01 13:37:42'),
            'empty datetime' => array(false, new TDatetime('date'), ''),

            'empty column name' => array(true, new TInt(''), 42),
        );
    }

    /**
     * @dataProvider providerTestEqualsField
     */
    public function testEqualsField($expected, $columnLeft, $columnRight)
    {
        $condition = new Equal($columnLeft, $columnRight);

        $this->assertSame($expected, $condition->toString($this->escaper));
    }

    public function providerTestEqualsField()
    {
        return array(
            array('pony = unicorn', new TString('pony'), new TString('unicorn'),),
            array('pony = id', new TString('pony'), new TInt('id'),),
            array('id = pony', new TInt('id'), new TString('pony'),),
            array('id = ponyId', new TInt('id'), new TInt('ponyId'),),
            array('creationDate = updateDate', new TDatetime('creationDate'), new TDatetime('updateDate'),),
            array('good = evil', new TBool('good'), new TBool('evil'),),
        );
    }
}