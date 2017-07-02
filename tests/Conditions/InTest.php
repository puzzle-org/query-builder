<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

use Puzzle\QueryBuilder\Type;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Types\TString;
use Puzzle\QueryBuilder\Types\TInt;
use Puzzle\QueryBuilder\Types\TDatetime;

class InTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    /**
     * @dataProvider providerTestIn
     */
    public function testIn($expected, Type $column, array $values)
    {
        $condition = new In($column, $values);

        $this->assertSame($expected, $condition->toString($this->escaper));
    }

    public function providerTestIn()
    {
        return array(
            'single int' => array("score IN (42)", new TInt('score'), [42]),
            'array of int' => array("score IN (42, 1337, 69)", new TInt('score'), [42, 1337, 69]),

            'single string' => array("race IN ('pony')", new TString('race'), ['pony']),
            'array of string' => array("score IN ('pony', 'unicorn', 'burger')", new TString('score'), ['pony', 'unicorn', 'burger']),

            'single datetime' => array("race IN ('2014-12-04 20:57:12')", new TDatetime('race'), ['2014-12-04 20:57:12']),
            'array of datetime' => array(
                "date IN ('2014-12-04 20:57:12', '1970-01-01 00:00:00', '1988-03-07 13:37:42')",
                new TDatetime('date'),
                [
                    '2014-12-04 20:57:12',
                    '1970-01-01 00:00:00',
                    \DateTime::createFromFormat('Y-m-d H:i:s', '1988-03-07 13:37:42')
                ]
            ),

            'mixed types' => array(
                "stuff IN ('pony', '666', '1988-03-07 13:37:42')",
                new TString('stuff'),
                [
                    'pony',
                    666,
                    '1988-03-07 13:37:42'
                ]
            ),

            'empty column' => array('', new TString(''), ['unicornz']),
        );
    }

    /**
     * @dataProvider providerTestIsEmpty
     */
    public function testIsEmpty($expected, Type $column, array $values)
    {
        $condition = new In($column, $values);

        $this->assertSame($expected, $condition->isEmpty());
    }

    public function providerTestIsEmpty()
    {
        return array(
            'simple string' => array(false, new TString('name'), ['poney']),
            'empty string' => array(false, new TString('name'), ['']),

            'simple int' => array(false, new TInt('id'), [42]),
            'empty int' => array(false, new TInt('id'), ['']),

            'simple datetime' => array(false, new TDatetime('date'), ['2014-12-01 13:37:42']),
            'empty datetime' => array(false, new TDatetime('date'), ['']),

            'empty column name' => array(true, new TString(''), [42]),
        );
    }
}