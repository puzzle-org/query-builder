<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets\Builders;

use Puzzle\QueryBuilder\Queries\Snippets;

trait Join
{
    protected
        $joins = array();

    public function innerJoin(string $table, ?string $alias = null): self
    {
        $this->joins[] = new Snippets\Joins\InnerJoin($table, $alias);

        return $this;
    }

    public function leftJoin(string $table, ?string $alias = null): self
    {
        $this->joins[] = new Snippets\Joins\LeftJoin($table, $alias);

        return $this;
    }

    public function rightJoin(string $table, ?string $alias = null): self
    {
        $this->joins[] = new Snippets\Joins\RightJoin($table, $alias);

        return $this;
    }

    public function on(string $leftColumn, string $rightColumn): self
    {
        $join = $this->getLastJoin();
        $join->on($leftColumn, $rightColumn);

        return $this;
    }

    /**
     * @param array[string]|string $column
     */
    public function using($column): self
    {
        $join = $this->getLastJoin();
        $join->using($column);

        return $this;
    }

    protected function buildJoin(): string
    {
        $joins = [];

        foreach($this->joins as $innerJoin)
        {
            $joins[] = $innerJoin->toString();
        }

        return implode(' ', $joins);
    }

    private function getLastJoin(): Snippets\Join
    {
        $lastJoins = end($this->joins);

        if(! $lastJoins instanceof Snippets\Join)
        {
            throw new \LogicException('Erreur dans la récupération de la dernière jointure');
        }

        return $lastJoins;
    }
}
