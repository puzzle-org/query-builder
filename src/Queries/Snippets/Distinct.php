<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\ValueObjects\Column;

class Distinct implements CountExpression
{
    private
        $column;

    public function __construct(Column $column)
    {
        $this->column = $column;
    }

    public function toString(): string
    {
        return sprintf('DISTINCT %s', $this->column->toString());
    }
}
