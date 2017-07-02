<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;

class On implements Snippet
{
    private
        $leftColumn,
        $rightColumn;

    public function __construct(?string $leftColumn, ?string $rightColumn)
    {
        $this->leftColumn = (string) $leftColumn;
        $this->rightColumn = (string) $rightColumn;
    }

    public function toString(): string
    {
        if(empty($this->leftColumn) || empty($this->rightColumn))
        {
            return '';
        }

        return sprintf(
            'ON %s = %s',
            $this->leftColumn,
            $this->rightColumn
        );
    }
}
