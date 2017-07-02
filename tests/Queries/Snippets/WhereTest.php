<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Condition;
use Puzzle\QueryBuilder\Conditions;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Types\TInt;
use Puzzle\QueryBuilder\Types\TString;

class WhereTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    /**
     * @dataProvider providerTestWhere
     */
    public function testWhere($expected, Condition $condition)
    {
        $where = new Where($condition);
        $where->setEscaper($this->escaper);

        $this->assertSame($expected, $where->toString());
    }

    public function providerTestWhere()
    {
        return array(
            'empty condition' => array('', new Conditions\Equal(new TString(''), '')),
            'simple string condition #1' => array("WHERE name = 'burger'", new Conditions\Equal(new TString('name'), 'burger')),
            'simple string condition #2' => array("WHERE name = '666'", new Conditions\Equal(new TString('name'), '666')),
            'simple int condition' => array("WHERE name = 666", new Conditions\Equal(new TInt('name'), 666)),
        );
    }
}