<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Collections\SelectExpressionCollections;

use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Collections\SelectExpressionCollection;
use Puzzle\QueryBuilder\ValueObjects\Column;
use Puzzle\QueryBuilder\Queries\Snippets\SelectExpression;
use Puzzle\QueryBuilder\Queries\Snippets\Count;

class UniqueSelectExpressionCollectionTest extends TestCase
{
    public function testUnique()
    {
        $collection = new UniqueSelectExpressionCollection();
        $this->assertCount(0, $collection);
        
        $collection->add(new Column('pony'));
        $this->assertCount(1, $collection);
        
        $collection->add(new Column('burger'));
        $this->assertCount(2, $collection);
        
        $collection->add(new Column('unicorn'));
        $this->assertCount(3, $collection);
        
        $collection->add(new Column('pony'));
        $this->assertCount(3, $collection);
        
        $collection->add(new Column('PONY'));
        $this->assertCount(4, $collection);
        
        $collection->add(new Column('burger'));
        $this->assertCount(4, $collection);
        
        $collection->add(new Column('unicorn'));
        $this->assertCount(4, $collection);
    }

    public function testMergeWith()
    {
        $c1 = new UniqueSelectExpressionCollection([
            new Column('a'),
            new Column('b'),
            new Count(new Column('z')),
            new Column('q'),
        ]);
    
        $c2 = new DuplicateAllowedSelectExpressionCollection([
            new Count(new Column('z')),
            new Column('x'),
            new Column('q'),
            new Column('y'),
        ]);
    
        $cUnique = clone $c1;
        $cDuplicated = clone $c2;
        
        $cUnique->add($c2);
        $cDuplicated->add($c1);
    
        $this->assertSameCollection(['a', 'b', 'COUNT(z)', 'q', 'x', 'y'], $cUnique);
        
        $this->assertSameCollection(['COUNT(z)', 'x', 'q', 'y', 'a', 'b','COUNT(z)', 'q'], $cDuplicated);
    }

    private function assertSameCollection(iterable $expected, SelectExpressionCollection $collection): void
    {
        $result = array_map(function (SelectExpression $c) {
            return $c->toString();
        }, iterator_to_array($collection->getIterator()));
    
        $this->assertSame($expected, $result);
    }
}
