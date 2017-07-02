<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;

class Update implements Snippet
{
    private
        $tables;

    public function __construct($table = null, ?string $alias = null)
    {
        $this->tables = array();

        if(! empty($table))
        {
            $this->addTable($table, $alias);
        }
    }

    public function addTable($table, ?string $alias = null): self
    {
        if(! $table instanceof TableName)
        {
            $table = new TableName($table, $alias);
        }

        $this->tables[] = $table;

        return $this;
    }

    public function toString(): string
    {
        if(empty($this->tables))
        {
            return '';
        }

        $tables = array();

        foreach($this->tables as $table)
        {
            $tables[] = $table->toString();
        }

        $tablesString = implode(', ', array_filter($tables));

        return sprintf('UPDATE %s', $tablesString);
    }
}
