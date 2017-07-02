<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

use Puzzle\QueryBuilder\Type;
use Puzzle\QueryBuilder\Types\TString;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use PHPUnit\Framework\TestCase;

class IsNotNullTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    /**
     * @dataProvider providerTestIsNotNull
     */
    public function testIsNotNull($expected, $column)
    {
        $condition = new IsNotNull($column);

        $this->assertSame($expected, $condition->toString($this->escaper));
    }

    public function providerTestIsNotNull()
    {
        return array(
            'nominal' => array('name IS NOT NULL', 'name'),
            'type' => array('name IS NOT NULL', new TString('name')),
            'empty column name' => array('', ''),
            'null column name' => array('', null),
        );
    }

    /**
     * @dataProvider providerTestIsEmpty
     */
    public function testIsEmpty($expected, $column)
    {
        $condition = new IsNotNull($column);

        $this->assertSame($expected, $condition->isEmpty());
    }

    public function providerTestIsEmpty()
    {
        return array(
            'string column name' => array(false, 'burger'),
            'type column name'   => array(false, new TString('ponyz')),
            'empty column name'  => array(true, ''),
            'null column name'   => array(true, null),
        );
    }
}
