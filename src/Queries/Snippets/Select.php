<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;
use Puzzle\QueryBuilder\Collections\SelectExpressionCollections\UniqueSelectExpressionCollection;

class Select implements Snippet
{
    private
        $expressions;

    public function __construct()
    {
        $this->expressions = new UniqueSelectExpressionCollection();
    }

    public function select(SelectExpressionArgument $expression): self
    {
        $this->expressions->add($expression);
    
        return $this;
    }

    public function toString(): string
    {
        if(count($this->expressions) === 0)
        {
            throw new \LogicException('No expressions defined for SELECT clause');
        }

        return sprintf('SELECT %s', $this->buildColumnsString());
    }

    private function buildColumnsString()
    {
        $expressions = array();

        foreach($this->expressions as $expression)
        {
            $expressions[] = $expression->toString();
        }

        return implode(', ', $expressions);
    }
}
