<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

use Puzzle\QueryBuilder\Type;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Types\TDatetime;
use Puzzle\QueryBuilder\Types\TString;
use Puzzle\QueryBuilder\Types\TInt;

class NotInTest extends TestCase
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
        $condition = new NotIn($column, $values);

        $this->assertSame($expected, $condition->toString($this->escaper));
    }

    public function providerTestIn()
    {
        return array(
            'single int' => array("score NOT IN (42)", new TInt('score'), [42]),
            'array of int' => array("score NOT IN (42, 1337, 69)", new TInt('score'), [42, 1337, 69]),

            'single string' => array("race NOT IN ('pony')", new TString('race'), ['pony']),
            'array of string' => array("score NOT IN ('pony', 'unicorn', 'burger')", new TString('score'), ['pony', 'unicorn', 'burger']),

            'single datetime' => array("date NOT IN ('2014-12-04 20:57:12')", new TDatetime('date'), ['2014-12-04 20:57:12']),
            'array of datetime' => array(
                "date NOT IN ('2014-12-04 20:57:12', '1970-01-01 00:00:00', '1988-03-07 13:37:42')",
                new TDatetime('date'),
                [
                    '2014-12-04 20:57:12',
                    '1970-01-01 00:00:00',
                    \DateTime::createFromFormat('Y-m-d H:i:s', '1988-03-07 13:37:42')
                ]
            ),

            'mixed types' => array(
                "stuff NOT IN ('pony', '666', '1988-03-07 13:37:42')",
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
}
