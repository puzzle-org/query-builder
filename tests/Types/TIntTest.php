<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Types;

use PHPUnit\Framework\TestCase;

class TIntTest extends TestCase
{
    /**
     * @dataProvider providerTestFormatInt
     */
    public function testFormatInt($expected, $value)
    {
        $type = new TInt('column_name');

        $this->assertSame($expected, $type->format($value));
    }

    public function providerTestFormatInt()
    {
        return array(
            'int'    => array(666, 666),
            'int string #1' => array(666, '666'),
            'int string #2' => array(666, '0666'),
            'float string' => array(1337, '1337.42'),
            'string' => array(0, 'poney'),
        );
    }
}