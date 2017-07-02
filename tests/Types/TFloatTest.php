<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Types;

use PHPUnit\Framework\TestCase;

class TFloatTest extends TestCase
{
    /**
     * @dataProvider providerTestFormatString
     */
    public function testFormatString($expected, $value)
    {
        $type = new TFloat('column_name');

        $this->assertSame($expected, $type->format($value));
    }

    public function providerTestFormatString()
    {
        return array(
            'float'           => array(13.37, 13.37),
            'float in string' => array(13.37, "13.37"),
            'int string'      => array(666.0, '666'),
            'string'          => array(0.0, 'poney'),
            'empty value'     => array(0.0, ''),
            'null value'      => array(0.0, null),
        );
    }
}