<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;
use Puzzle\QueryBuilder\ValueObjects\Table;

class From implements Snippet
{
    private
        $tableName;

    public function __construct(Table $table)
    {
        $this->tableName = $table;
    }

    public function toString(): string
    {
        return sprintf('FROM %s', (string) $this->tableName);
    }
}
