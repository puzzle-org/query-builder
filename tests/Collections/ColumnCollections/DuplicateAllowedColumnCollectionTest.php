<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Collections\ColumnCollections;

use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\ValueObjects\Column;
use Puzzle\QueryBuilder\Collections\ColumnCollection;

class DuplicateAllowedColumnCollectionTest extends TestCase
{
    public function testUnique()
    {
        $collection = new DuplicateAllowedColumnCollection();
        
        $collection
            ->add(new Column('pony'))
            ->add(new Column('burger'))
            ->add(new Column('unicorn'))
            ->add(new Column('deuteranope'))
            ->add(new Column('pony'))
            ->add(new Column('pizza'))
            ->add(new Column('PONY'))
            ->add(new Column('burger'))
            ->add(new Column('unicorn'))
            ->add(new Column('deuteranope rex'));
        
        $this->assertSameCollection([
            'pony', 'burger', 'unicorn', 'deuteranope', 'pizza', 'PONY', 'deuteranope rex',
        ], $collection->unique());
    }
    
    public function testMergeWith()
    {
        $c1 = new DuplicateAllowedColumnCollection([
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
        
        $c1->mergeWith($c2);
        
        $this->assertCount(8, $c1);
        $this->assertSameCollection(['a', 'b', 'c', 'q', 'x', 'q', 'y', 'z'], $c1);
    }
    
    private function assertSameCollection(iterable $expected, ColumnCollection $collection): void
    {
        $result = array_map(function (Column $c) {
            return $c->getName();
        }, iterator_to_array($collection->getIterator()));
        
        $this->assertSame($expected, $result);
    }
}
