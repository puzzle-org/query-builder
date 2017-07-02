<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;

class SetTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    public function testSet()
    {
        $part = new Set();
        $part->setEscaper($this->escaper);

        $part->set(array('name' => 'burger'));
        $this->assertSame("SET name = 'burger'", $part->toString());

        $part->set(array(
            'rank' => '42',
            'score' => 1337
        ));
        $this->assertSame("SET name = 'burger', rank = '42', score = 1337", $part->toString());

        $part->set(array());
        $this->assertSame("SET name = 'burger', rank = '42', score = 1337", $part->toString());

        $part->set(array(
            'flag' => true
        ));
        $this->assertSame("SET name = 'burger', rank = '42', score = 1337, flag = 1", $part->toString());
    }

    public function testEmptySet()
    {
        $part = new Set();
        $part->setEscaper($this->escaper);

        $this->assertSame('', $part->toString());
    }
}