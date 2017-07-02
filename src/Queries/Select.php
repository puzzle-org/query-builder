<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries;

use Puzzle\QueryBuilder\Query;
use Puzzle\QueryBuilder\Condition;
use Puzzle\QueryBuilder\Traits\EscaperAware;
use Puzzle\QueryBuilder\Snippet;
use Puzzle\QueryBuilder\Queries\Snippets\Builders;
use Puzzle\QueryBuilder\Queries\Snippets\Having;

class Select implements Query
{
    use
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
     * @param array[string|Selectable] | string|Selectable  $columns
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

    /**
     * @param Snippets\TableName|string $table
     */
    public function from($table, ?string $alias = null): self
    {
        $this->from = new Snippets\From($table, $alias);

        return $this;
    }

    /**
     * @param array[string|Selectable] | string|Selectable  $columns
     */
    public function select($columns): self
    {
        $this->select->select($columns);

        return $this;
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
