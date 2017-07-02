<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets\Builders;

trait GroupBy
{
    protected
        $groupBy;

    public function groupBy(?string $column): self
    {
        $this->groupBy->addGroupBy($column);

        return $this;
    }

    private function buildGroupBy(): string
    {
        return $this->groupBy->toString();
    }
}
