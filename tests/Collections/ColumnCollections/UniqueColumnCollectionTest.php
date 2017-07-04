<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Collections\ColumnCollections;

use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\ValueObjects\Column;
use Puzzle\QueryBuilder\Collections\ColumnCollection;

class UniqueColumnCollectionTest extends TestCase
{
    public function testUnique()
    {
        $collection = new UniqueColumnCollection();
        $this->assertCount(0, $collection);
        
        $collection->add(new Column('pony'));
        $this->assertCount(1, $collection);
        
        $collection->add(new Column('burger'));
        $this->assertCount(2, $collection);
        
        $collection->add(new Column('unicorn'));
        $this->assertCount(3, $collection);
        
        $collection->add(new Column('deuteranope'));
        $this->assertCount(4, $collection);
        
        $collection->add(new Column('pony'));
        $this->assertCount(4, $collection);
        
        $collection->add(new Column('pizza'));
        $this->assertCount(5, $collection);
        
        $collection->add(new Column('PONY'));
        $this->assertCount(6, $collection);
        
        $collection->add(new Column('burger'));
        $this->assertCount(6, $collection);
        
        $collection->add(new Column('unicorn'));
        $this->assertCount(6, $collection);
        
        $collection->add(new Column('deuteranope rex'));
        $this->assertCount(7, $collection);
    }

    public function testMergeWith()
    {
        $c1 = new UniqueColumnCollection([
            new Column('a'),
            new Column('b'),
            new Column('c'),
            new Column('q'),
        ]);
    
        $c2 = new DuplicateAllowedColumnCollection([
            new Column('x'),
            new Column('q'),
            new Column('y'),
            new Column('z'),
        ]);
    
        $cUnique = clone $c1;
        $cDuplicated = clone $c2;
        
        $cUnique->mergeWith($c2);
        $cDuplicated->mergeWith($c1);
    
        $this->assertCount(7, $cUnique);
        $this->assertSameCollection(['a', 'b', 'c', 'q', 'x', 'y', 'z'], $cUnique);
        
        $this->assertCount(8, $cDuplicated);
        $this->assertSameCollection(['x', 'q', 'y', 'z', 'a', 'b', 'c', 'q'], $cDuplicated);
    }

    private function assertSameCollection(iterable $expected, ColumnCollection $collection): void
    {
        $result = array_map(function (Column $c) {
            return $c->getName();
        }, iterator_to_array($collection->getIterator()));
    
        $this->assertSame($expected, $result);
    }
}
