<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Types;

use PHPUnit\Framework\TestCase;

class TDatetimeTest extends TestCase
{
    /**
     * @dataProvider providerTestFormatDatetime
     */
    public function testFormatDatetime($expected, $value)
    {
        $type = new TDatetime('column_name');

        $this->assertSame($expected, $type->format($value));
    }

    public function providerTestFormatDatetime()
    {
        return array(
            'string' => array('2014-12-10 13:37:42', '2014-12-10 13:37:42'),
            'empty value' => array('', ''),
            'datetime #1' => array('2014-12-10 13:37:42', \DateTime::createFromFormat('Y-m-d H:i:s', '2014-12-10 13:37:42')),
        );
    }
}