<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Collections\ColumnCollections;

use Puzzle\QueryBuilder\Collections\ColumnCollection;
use Puzzle\QueryBuilder\ValueObjects\Column;

final class DuplicateAllowedColumnCollection implements ColumnCollection
{
    private
        $columns;
        
    public function __construct(iterable $columns = [])
    {
        $this->columns = [];
        
        foreach($columns as $column)
        {
            if(! $column instanceof Column)
            {
                throw new \InvalidArgumentException("Invalid type in " . __CLASS__);
            }
            
            $this->add($column);
        }
    }
    
    public function add(Column $column): ColumnCollection
    {
        $this->columns[] = $column;
        
        return $this;
    }
    
    public function mergeWith(ColumnCollection $collection): void
    {
        foreach($collection as $column)
        {
            $this->add($column);
        }
    }
    
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->columns);
    }
    
    public function count(): int
    {
        return count($this->columns);
    }
    
    public function unique(): UniqueColumnCollection
    {
        return new UniqueColumnCollection($this->columns);
    }
}
