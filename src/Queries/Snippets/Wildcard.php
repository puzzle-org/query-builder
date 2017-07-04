<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

class Wildcard implements CountExpression, SelectExpression
{
    public function toString(): string
    {
        return '*';
    }
    
    public function equals(SelectExpression $expr): bool
    {
        return $expr instanceof self;
    }
}
