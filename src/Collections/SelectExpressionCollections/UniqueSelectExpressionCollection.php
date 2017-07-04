<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Collections\SelectExpressionCollections;

use Puzzle\QueryBuilder\Queries\Snippets\SelectExpression;
use Puzzle\QueryBuilder\Collections\SelectExpressionCollection;
use Puzzle\QueryBuilder\Queries\Snippets\SelectExpressionArgument;

final class UniqueSelectExpressionCollection implements SelectExpressionCollection
{
    private
        $expressions;
        
    public function __construct(iterable $expressions = [])
    {
        $this->expressions = [];
        
        foreach($expressions as $expression)
        {
            if(! $expression instanceof SelectExpression)
            {
                throw new \InvalidArgumentException("Invalid type in " . __CLASS__);
            }
            
            $this->add($expression);
        }
    }
    
    public function add(SelectExpressionArgument $expression): SelectExpressionCollection
    {
        if($expression instanceof SelectExpressionCollection)
        {
            $this->mergeWith($expression);
        }
        else
        {
            $this->addExpression($expression);
        }
        
        return $this;
    }
    
    private function addExpression(SelectExpression $expression): void
    {
        if($this->exists($expression) === false)
        {
            $this->expressions[] = $expression;
        }
    }
    
    private function mergeWith(SelectExpressionCollection $collection): void
    {
        foreach($collection as $expression)
        {
            $this->addExpression($expression);
        }
    }
    
    private function exists(SelectExpression $searched): bool
    {
        foreach($this->expressions as $expression)
        {
            if($expression->equals($searched))
            {
                return true;
            }
        }
        
        return false;
    }
    
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->expressions);
    }
    
    public function count(): int
    {
        return count($this->expressions);
    }
}
