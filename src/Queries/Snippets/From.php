<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;

class From implements Snippet, NeedTableAware
{
    private
        $tableName;

    /**
     * @param TableName|string $table
     */
    public function __construct($table, ?string $alias = null)
    {
        if(! $table instanceof TableName)
        {
            $table = new TableName($table, $alias);
        }

        $this->tableName = $table;
    }

    public function toString(): string
    {
        return sprintf('FROM %s', $this->tableName->toString());
    }

    public function hasNeededTable(string $tableName): bool
    {
        if($this->tableName->getName() === $tableName || $this->tableName->getAlias() === $tableName)
        {
            return true;
        }

        return false;
    }
}
