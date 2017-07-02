<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;

class Count implements Snippet, Selectable
{
    private
        $columnName,
        $alias;

    /**
     * @param Snippet|string $columnName
     */
    public function __construct($columnName, ?string $alias = null)
    {
        if((! $columnName instanceof Snippet) && empty($columnName))
        {
            throw new \InvalidArgumentException('Empty column name.');
        }

        $this->columnName = $columnName;
        $this->alias = $alias;
    }

    public function toString(): string
    {
        return implode(' ', array_filter(array(
            $this->buildCountSnippet(),
            $this->buildAliasSnippet()
        )));
    }

    private function buildCountSnippet(): string
    {
        $columnName = $this->columnName;

        if($columnName instanceof Snippet)
        {
            $columnName = $columnName->toString();
        }

        return sprintf('COUNT(%s)', $columnName);
    }

    private function buildAliasSnippet(): string
    {
        $alias = $this->alias;

        if(! empty($alias))
        {
            return sprintf('AS %s', $alias);
        }

        return '';
    }
}
