<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;

class TableName implements Snippet
{
    private
        $tableName,
        $alias;

    public function __construct(?string $tableName, ?string $alias = null)
    {
        if(empty($tableName))
        {
            throw new \InvalidArgumentException('Empty table name.');
        }

        $this->tableName = $tableName;

        $this->alias = (string) $alias;
    }

    public function toString(): string
    {
        if(empty($this->alias))
        {
            return $this->tableName;
        }

        return sprintf('%s AS %s', $this->tableName, $this->alias);
    }
}
