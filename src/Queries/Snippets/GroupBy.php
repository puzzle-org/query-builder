<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;

class GroupBy implements Snippet
{
    private
        $groupBy;

    public function __construct()
    {
        $this->groupBy = array();
    }

    public function addGroupBy(?string $column): self
    {
        if(!empty($column))
        {
            $this->groupBy[$column] = $column;
        }

        return $this;
    }

    public function toString(): string
    {
        if(empty($this->groupBy))
        {
            return '';
        }

        return sprintf('GROUP BY %s', implode(', ', $this->groupBy));
    }
}
