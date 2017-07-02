<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;

class Distinct implements Snippet
{
    private
        $columnName;

    public function __construct(?string $columnName)
    {
        if(empty($columnName))
        {
            throw new \InvalidArgumentException('Empty column name.');
        }

        $this->columnName = (string) $columnName;
    }

    public function toString(): string
    {
        return sprintf('DISTINCT %s', $this->columnName);
    }
}
