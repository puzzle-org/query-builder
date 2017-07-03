<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries;

use Puzzle\QueryBuilder\Query;
use Puzzle\QueryBuilder\Traits\EscaperAware;
use Puzzle\QueryBuilder\Queries\Snippets\Builders;
use Puzzle\QueryBuilder\QueryPartAware;

class Update implements Query, QueryPartAware
{
    use
        EscaperAware,
        Builders\Join,
        Builders\Where,
        Builders\OrderBy,
        Builders\Limit,
        Builders\QueryPart;

    private
        $updatePart,
        $sets;

    /**
     * @param Snippets\TableName|string|null $table
     */
    public function __construct($table = null, ?string $alias = null)
    {
        $this->updatePart = new Snippets\Update();
        $this->where = new Snippets\Where();
        $this->sets = new Snippets\Set();
        $this->orderBy = new Snippets\OrderBy();

        if(! empty($table))
        {
            $this->update($table, $alias);
        }
    }

    public function toString(): string
    {
        $snippets = $this->joins;
        $snippets[] = $this->updatePart;
        $this->ensureNeededTablesArePresent($snippets);

        $queryParts = array(
            $this->buildUpdate(),
            $this->buildJoin(),
            $this->buildSets(),
            $this->buildWhere($this->escaper),
            $this->buildOrderBy(),
            $this->buildLimit(),
        );

        return implode(' ', array_filter($queryParts));
    }

    /**
     * @param Snippets\TableName|string $table
     */
    public function update($table, ?string $alias = null): self
    {
        $this->updatePart->addTable($table, $alias);

        return $this;
    }

    public function set(array $fields): self
    {
        $this->sets->set($fields);

        return $this;
    }

    private function buildUpdate(): string
    {
        $updateString = $this->updatePart->toString();

        if(empty($updateString))
        {
            throw new \RuntimeException('No table defined');
        }

        return $updateString;
    }

    private function buildSets(): string
    {
        $this->sets->setEscaper($this->escaper);

        return $this->sets->toString();
    }
}
