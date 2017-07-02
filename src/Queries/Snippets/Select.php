<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;

class Select implements Snippet
{
    private
        $columns;

    /**
     * @param array[string|Selectable] | string|Selectable  $columns
     */
    public function __construct($columns = [])
    {
        $this->columns = [];

        $this->addColumns($columns);
    }

    /**
     * @param array[string|Selectable] | string|Selectable  $columns
     */
    public function select($columns): self
    {
        $this->addColumns($columns);

        return $this;
    }

    public function toString(): string
    {
        if(empty($this->columns))
        {
            throw new \LogicException('No columns defined for SELECT clause');
        }

        return sprintf('SELECT %s', $this->buildColumnsString());
    }

    private function buildColumnsString()
    {
        $columns = array();

        foreach($this->columns as $column)
        {
            if($column instanceof Selectable)
            {
                $column = $column->toString();
            }

            $columns[] = $column;
        }

        return implode(', ', array_unique($columns));
    }

    private function addColumns($columns): void
    {
        $columns = array_filter($this->ensureIsArray($columns));

        $this->validateColumns($columns);

        $this->columns = array_merge($this->columns, $columns);
    }

    private function validateColumns($columns)
    {
        foreach($columns as $column)
        {
            if(! is_string($column) && (!$column instanceof Selectable))
            {
                throw new \InvalidArgumentException('Column name must be a string.');
            }
        }
    }

    private function ensureIsArray($select): array
    {
        if(! is_array($select))
        {
            $select = array($select);
        }

        return $select;
    }
}
