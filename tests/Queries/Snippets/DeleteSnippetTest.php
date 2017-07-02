<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use PHPUnit\Framework\TestCase;

class DeleteSnippetTest extends TestCase
{
    /**
     * @dataProvider providerTestDeleteViaConstructor
     */
    public function testDeleteViaConstructor($expected, $columns)
    {
        $select = new Delete($columns);

        $this->assertSame($expected, $select->toString());
    }

    public function providerTestDeleteViaConstructor()
    {
        return array(
            'single table'     => array('DELETE FROM burger', 'burger'),
        );
    }
}