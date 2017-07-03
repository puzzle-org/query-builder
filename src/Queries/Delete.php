<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries;

use Puzzle\QueryBuilder\Query;
use Puzzle\QueryBuilder\Traits\EscaperAware;
use Puzzle\QueryBuilder\Snippet;
use Puzzle\QueryBuilder\Queries\Snippets\Builders;
use Puzzle\QueryBuilder\QueryPartAware;

class Delete implements Query, QueryPartAware
{
    use
        EscaperAware,
        Builders\Join,
        Builders\Where,
        Builders\OrderBy,
        Builders\Limit,
        Builders\QueryPart;

    private
        $from;

    /**
     * @param TableName|string $table
     */
    public function __construct($table = null, ?string $alias = null)
    {
        if(!empty($table))
        {
            $this->from($table, $alias);
        }

        $this->where = new Snippets\Where();
        $this->orderBy = new Snippets\OrderBy();
    }

    public function toString(): string
    {
        $snippets = $this->joins;
        $snippets[] = $this->from;
        $this->ensureNeededTablesArePresent($snippets);

        $queryParts = array(
            'DELETE',
            $this->buildFrom(),
            $this->buildJoin(),
            $this->buildWhere($this->escaper),
            $this->buildOrderBy(),
            $this->buildLimit(),
        );

        return implode(' ', array_filter($queryParts));
    }

    /**
     * @param TableName|string $table
     */
    public function from($table, ?string $alias = null): self
    {
        $this->from = new Snippets\From($table, $alias);

        return $this;
    }

    private function buildFrom(): string
    {
        if(!$this->from instanceof Snippet)
        {
            throw new \LogicException('No column for FROM clause');
        }

        return $this->from->toString();
    }
}
