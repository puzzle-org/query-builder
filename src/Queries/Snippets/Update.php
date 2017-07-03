<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;
use Puzzle\QueryBuilder\ValueObjects\Table;

class Update implements Snippet
{
    private
        $tables;

    public function __construct()
    {
        $this->tables = [];
    }

    public function addTable(Table $table): self
    {
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
            $tables[] = (string) $table;
        }

        $tablesString = implode(', ', array_filter($tables));

        return sprintf('UPDATE %s', $tablesString);
    }
}
