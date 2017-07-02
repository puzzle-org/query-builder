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

class DifferentTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    /**
     * @dataProvider providerTestDifferent
     */
    public function testDifferent($expected, Type $column, $value)
    {
        $condition = new Different($column, $value);

        $this->assertSame($expected, $condition->toString($this->escaper));
    }

    public function providerTestDifferent()
    {
        return array(
            'simple string' => array("name != 'poney'", new TString('name'), 'poney'),
            'empty string' => array("name != ''", new TString('name'), ''),

            'simple int' => array("id != 42", new TInt('id'), 42),
            'empty int' => array("id != 0", new TInt('id'), ''),

            'simple datetime' => array("date != '2014-12-01 13:37:42'", new TDatetime('date'), '2014-12-01 13:37:42'),
            'empty datetime' => array("date != ''", new TDatetime('date'), ''),

            'empty column name' => array('', new TInt(''), 42),
        );
    }

    /**
     * @dataProvider providerTestDifferentsField
     */
    public function testDifferentsField($expected, $columnLeft, $columnRight)
    {
        $condition = new Different($columnLeft, $columnRight);

        $this->assertSame($expected, $condition->toString($this->escaper));
    }

    public function providerTestDifferentsField()
    {
        return array(
            array('pony != unicorn', new TString('pony'), new TString('unicorn'),),
            array('pony != id', new TString('pony'), new TInt('id'),),
            array('id != pony', new TInt('id'), new TString('pony'),),
            array('id != ponyId', new TInt('id'), new TInt('ponyId'),),
            array('creationDate != updateDate', new TDatetime('creationDate'), new TDatetime('updateDate'),),
            array('good != evil', new TBool('good'), new TBool('evil'),),
        );
    }
}