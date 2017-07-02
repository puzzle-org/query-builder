<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets\Builders;

use Puzzle\QueryBuilder\Queries\Snippets;

trait OrderBy
{
    protected
        $orderBy;

    public function orderBy(string $column, string $direction = Snippets\OrderBy::ASC): self
    {
        $this->orderBy->addOrderBy($column, $direction);

        return $this;
    }

    private function buildOrderBy(): string
    {
        return $this->orderBy->toString();
    }
}
