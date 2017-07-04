<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries;

use Puzzle\QueryBuilder\Query;
use Puzzle\QueryBuilder\Condition;
use Puzzle\QueryBuilder\Traits\EscaperAware;
use Puzzle\QueryBuilder\Snippet;
use Puzzle\QueryBuilder\Queries\Snippets\Builders;
use Puzzle\QueryBuilder\Queries\Snippets\Having;
use Puzzle\QueryBuilder\ValueObjects\Table;
use Puzzle\QueryBuilder\Queries\Snippets\SelectExpressionArgument;
use Puzzle\QueryBuilder\Queries\Snippets\SelectExpression;
use Puzzle\Pieces\StringManipulation;
use Puzzle\QueryBuilder\ValueObjects\Column;
use Puzzle\QueryBuilder\Collections\SelectExpressionCollections\DuplicateAllowedSelectExpressionCollection;

class Select implements Query
{
    use
        StringManipulation,
        EscaperAware,
        Builders\Join,
        Builders\Where,
        Builders\GroupBy,
        Builders\OrderBy,
        Builders\Limit;

    private
        $select,
        $from,
        $having;

    /**
     * @param array[string]|string $columns
     */
    public function __construct($columns = [])
    {
        $this->select = new Snippets\Select();
        $this->where = new Snippets\Where();
        $this->groupBy = new Snippets\GroupBy();
        $this->having = new Snippets\Having();
        $this->orderBy = new Snippets\OrderBy();

        $this->select($columns);
    }

    public function toString(): string
    {
        $queryParts = array(
            $this->buildSelect(),
            $this->buildFrom(),
            $this->buildJoin(),
            $this->buildWhere($this->escaper),
            $this->buildGroupBy(),
            $this->buildHaving(),
            $this->buildOrderBy(),
            $this->buildLimit(),
        );

        return implode(' ', array_filter($queryParts));
    }

    public function from(string $table, ?string $alias = null): self
    {
        $this->from = new Snippets\From(
            new Table($table, $alias)
        );

        return $this;
    }

    /**
     * @param array[string]|string $expression
     */
    public function select($expression): self
    {
        $this->select->select(
            $this->convertSelectExpression($expression)
        );

        return $this;
    }
    
    /**
     * @param array[string]|string $expression
     */
    private function convertSelectExpression($expression): SelectExpressionArgument
    {
        if(! is_array($expression))
        {
            return $this->convertOneSelectExpression($expression);
        }
        
        $collection = new DuplicateAllowedSelectExpressionCollection();
        
        foreach($expression as $expr)
        {
            $collection->add($this->convertOneSelectExpression($expr));
        }
        
        return $collection;
    }
    
    /**
     * @param string $expression
     */
    private function convertOneSelectExpression($expression): SelectExpression
    {
        if($expression instanceof SelectExpressionArgument)
        {
            return $expression;
        }
        
        if($this->isConvertibleToString($expression))
        {
            return new Column($expression);
        }
        
        throw new \InvalidArgumentException("Invalid select expression");
    }

    public function having(Condition $condition): self
    {
        $this->having->having($condition);

        return $this;
    }

    private function buildSelect(): string
    {
        return $this->select->toString();
    }

    private function buildFrom(): string
    {
        if(!$this->from instanceof Snippet)
        {
            throw new \LogicException('No column for FROM clause');
        }

        return $this->from->toString();
    }

    private function buildHaving(): string
    {
        $this->having->setEscaper($this->escaper);

        return $this->having->toString();
    }
}
