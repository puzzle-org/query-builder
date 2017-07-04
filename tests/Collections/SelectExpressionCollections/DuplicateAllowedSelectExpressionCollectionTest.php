<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Collections\SelectExpressionCollections;

use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Collections\SelectExpressionCollection;
use Puzzle\QueryBuilder\ValueObjects\Column;
use Puzzle\QueryBuilder\Queries\Snippets\SelectExpression;
use Puzzle\QueryBuilder\Queries\Snippets\Count;

class DuplicateAllowedSelectExpressionCollectionTest extends TestCase
{
    public function testUnique()
    {
        $collection = new DuplicateAllowedSelectExpressionCollection();
        
        $collection
            ->add(new Column('pony'))
            ->add(new Column('burger'))
            ->add(new Column('unicorn'))
            ->add(new Count(new Column('pony')))
            ->add(new Column('pony'))
            ->add(new Column('PONY'))
            ->add(new Column('burger'))
            ->add(new Column('unicorn'));
        
        $this->assertSameCollection([
            'pony', 'burger', 'unicorn', 'COUNT(pony)', 'PONY',
        ], $collection->unique());
    }
    
    public function testMergeWith()
    {
        $c1 = new DuplicateAllowedSelectExpressionCollection([
            new Column('a'),
            new Column('b'),
            new Count(new Column('pony')),
            new Column('q'),
        ]);
        
        $c2 = new DuplicateAllowedSelectExpressionCollection([
            new Count(new Column('pony')),
            new Column('x'),
            new Column('q'),
            new Column('y'),
        ]);
        
        $c1->add($c2);
        
        $this->assertCount(8, $c1);
        $this->assertSameCollection(['a', 'b', 'COUNT(pony)', 'q', 'COUNT(pony)', 'x', 'q', 'y'], $c1);
    }
    
    private function assertSameCollection(iterable $expected, SelectExpressionCollection $collection): void
    {
        $result = array_map(function (SelectExpression $c) {
            return $c->toString();
        }, iterator_to_array($collection->getIterator()));
        
        $this->assertSame($expected, $result);
    }
}
