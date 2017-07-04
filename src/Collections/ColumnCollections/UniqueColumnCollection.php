<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Collections\ColumnCollections;

use Puzzle\QueryBuilder\ValueObjects\Column;
use Puzzle\QueryBuilder\Collections\ColumnCollection;

final class UniqueColumnCollection implements ColumnCollection
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
        if($this->exists($column) === false)
        {
            $this->columns[] = $column;
        }
        
        return $this;
    }
    
    public function mergeWith(ColumnCollection $collection): void
    {
        foreach($collection as $column)
        {
            $this->add($column);
        }
    }
    
    private function exists(Column $searched): bool
    {
        foreach($this->columns as $column)
        {
            if($column->equals($searched))
            {
                return true;
            }
        }
        
        return false;
    }
    
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->columns);
    }
    
    public function count(): int
    {
        return count($this->columns);
    }
}
