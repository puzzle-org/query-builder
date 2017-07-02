<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Types;

use PHPUnit\Framework\TestCase;

class TBoolTest extends TestCase
{
    /**
     * @dataProvider providerTestFormatBoolean
     */
    public function testFormatBoolean($expected, $value)
    {
        $type = new TBool('column_name');

        $this->assertSame($expected, $type->format($value));
    }

    public function providerTestFormatBoolean()
    {
        return array(
            'boolean, false'   => array(0, false),
            'boolean, true'    => array(1, true),

            'integer in string, false'   => array(0, '0'),
            'integer in string, true'    => array(1, '1'),
            'integer, false'   => array(0, 0),
            'integer, true'    => array(1, 1),

            'null'    => array(0, null),
            'empty string'    => array(0, ''),
            'string'    => array(1, 'pony'),
            'string, false'    => array(1, 'false'),
            'string, true'    => array(1, 'true'),
        );
    }
}