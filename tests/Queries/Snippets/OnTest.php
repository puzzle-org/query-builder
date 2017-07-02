<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use PHPUnit\Framework\TestCase;

class OnTest extends TestCase
{
    /**
     * @dataProvider providerTestOn
     */
    public function testOn($expected, $leftColumn, $rightColumn)
    {
        $part = new On($leftColumn, $rightColumn);

        $this->assertSame($expected, $part->toString());
    }

    public function providerTestOn()
    {
        return array(
            'nominal' => array('ON poney = licorne', 'poney', 'licorne'),
            'nominal, with alias' => array('ON p.poney = l.licorne', 'p.poney', 'l.licorne'),

            'empty left column' => array('', '', 'licorne'),
            'null left column' => array('', null, 'licorne'),

            'empty right column' => array('', 'poney', ''),
            'null right column' => array('', 'poney', null),

            'empty columns' => array('', '', ''),
            'null columns' => array('', null, null),
        );
    }
}