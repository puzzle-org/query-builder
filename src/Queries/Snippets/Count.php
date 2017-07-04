<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;

class Count implements Snippet, SelectExpression
{
    private
        $expression,
        $alias;

    public function __construct(CountExpression $expression, ?string $alias = null)
    {
        $this->expression = $expression;
        $this->alias = $alias;
    }

    public function toString(): string
    {
        return implode(' ', array_filter(array(
            $this->buildCountSnippet(),
            $this->buildAliasSnippet()
        )));
    }
    
    public function equals(SelectExpression $expr): bool
    {
        if($expr instanceof self)
        {
            return $this->toString() === $expr->toString();
        }
        
        return false;
    }

    private function buildCountSnippet(): string
    {
        return sprintf('COUNT(%s)', $this->expression->toString());
    }

    private function buildAliasSnippet(): string
    {
        $alias = $this->alias;

        if(! empty($alias))
        {
            return sprintf('AS %s', $alias);
        }

        return '';
    }
}
