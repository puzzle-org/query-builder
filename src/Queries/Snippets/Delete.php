<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;

class Delete implements Snippet
{
    private
        $tableName;

    public function __construct(string $tableName)
    {
        $this->tableName = new TableName($tableName);
    }

    public function toString(): string
    {
        $from = new From($this->tableName);

        return sprintf('DELETE %s', $from->toString());
    }
}
