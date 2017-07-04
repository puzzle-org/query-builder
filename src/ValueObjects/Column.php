<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\ValueObjects;

use Puzzle\QueryBuilder\Queries\Snippets\SelectExpression;
use Puzzle\QueryBuilder\Queries\Snippets\CountExpression;

/**
 * @valueObject
 */
final class Column implements SelectExpression, CountExpression
{
    private
        $name;
        
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function toString(): string
    {
        return $this->name;
    }
    
    public function equals(SelectExpression $expr): bool
    {
        if($expr instanceof self)
        {
            return $this->name === $expr->name;
        }
            
        return false;
    }
}
