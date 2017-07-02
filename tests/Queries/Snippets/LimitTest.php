<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use PHPUnit\Framework\TestCase;

class LimitTest extends TestCase
{
    /**
     * @dataProvider providerTestLimit
     */
    public function testLimit($expected, $limit)
    {
        $qb = new Limit($limit);

        $this->assertSame($expected, $qb->toString());
    }

    public function providerTestLimit()
    {
        return array(
            'null limit' => array('', null),
            'empty limit' => array('', ''),
            'limit string' => array('', 'poney'),

            'simple limit' => array('LIMIT 1337', 1337),
            'simple limit, int limit as string' => array('LIMIT 42', '42'),
            'simple limit, float limit' => array('', '42.12'),
        );
    }
}