<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets\Joins;

use Puzzle\QueryBuilder\Snippet;
use Puzzle\QueryBuilder\Queries\Snippets\Join;
use Puzzle\QueryBuilder\Queries\Snippets;

abstract class AbstractJoin implements Join, Snippet
{
    private
        $table,
        $using,
        $on;

    public function __construct(string $table, ?string $alias = null)
    {
        $this->table = new Snippets\TableName($table, $alias);
        $this->on = [];
    }

    /**
     * @param array[string]|string $column
     */
    public function using($column): self
    {
        $this->on = [];

        $this->using = new Snippets\Using($column);

        return $this;
    }

    public function on(?string $leftColumn, ?string $rightColumn): self
    {
        $this->using = null;
        $this->on[] = new Snippets\On($leftColumn, $rightColumn);

        return $this;
    }

    public function toString(): string
    {
        $joinQueryPart = sprintf(
            '%s %s',
            $this->getJoinDeclaration(),
            $this->table->toString()
        );

        $joinQueryPart .= $this->buildOnConditionClause();
        $joinQueryPart .= $this->buildUsingConditionClause();

        return $joinQueryPart;
    }

    abstract protected function getJoinDeclaration(): string;

    private function buildUsingConditionClause(): string
    {
        if(!$this->using instanceof Snippet)
        {
            return '';
        }

        return ' ' . $this->using->toString();
    }

    private function buildOnConditionClause(): string
    {
        $conditionClause = array();

        foreach($this->on as $on)
        {
            if($on instanceof Snippet)
            {
                $conditionClause[] = $on->toString();
            }
        }

        return empty($conditionClause) ? '' : ' ' . implode('', $conditionClause);
    }
}
