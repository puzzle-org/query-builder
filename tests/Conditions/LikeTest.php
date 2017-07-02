<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

use Puzzle\QueryBuilder\Type;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Types\TString;
use Puzzle\QueryBuilder\Types\TInt;

class LikeTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    /**
     * @dataProvider providerTestLike
     */
    public function testLike($expected, Type $column, $value)
    {
        $condition = new Like($column, $value);

        $this->assertSame($expected, $condition->toString($this->escaper));
    }

    public function providerTestLike()
    {
        return array(
            'simple string' => array("taste LIKE 'burger'", new TString('taste'), 'burger'),
            'empty string'  => array("name LIKE ''", new TString('name'), ''),

            'simple string with starting wildcard' => array("name LIKE '%poney'", new TString('name'), '%poney'),
            'simple string with ending wildcard' => array("name LIKE 'poney%'", new TString('name'), 'poney%'),
            'simple string wrapped with wildcard' => array("name LIKE '%burger%'", new TString('name'), '%burger%'),

            'simple int'    => array("id LIKE 1337", new TInt('id'), 1337),
            'empty int'     => array('id LIKE 0', new TInt('id'), ''),

            'empty column name' => array('', new TString(''), 'poney'),
            'empty value' => array('', new TString(''), 'poney'),
        );
    }
}