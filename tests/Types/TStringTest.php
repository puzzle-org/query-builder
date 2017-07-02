<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Types;

use PHPUnit\Framework\TestCase;

class TStringTest extends TestCase
{
    /**
     * @dataProvider providerTestFormatString
     */
    public function testFormatString($expected, $value)
    {
        $type = new TString('column_name');

        $this->assertSame($expected, $type->format($value));
    }

    public function providerTestFormatString()
    {
        return array(
            'int'           => array("666", 666),
            'int string #1' => array("666", '666'),
            'int string #2' => array("666", '666'),
            'float string'  => array("1337.42", '1337.42'),
            'string'        => array("poney", 'poney'),
        );
    }
}