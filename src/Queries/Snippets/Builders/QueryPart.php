<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets\Builders;

use Puzzle\QueryBuilder\Queries\Snippets\NeedTableAware;
use Puzzle\QueryBuilder\Query;

trait QueryPart
{
    private
        $neededTableNames = [];

    public function add(\Puzzle\QueryBuilder\QueryPart $queryPart): Query
    {
        $queryPart->build($this);

        return $this;
    }

    public function needTable($tableName): Query
    {
        if(! in_array($tableName, $this->neededTableNames))
        {
            $this->neededTableNames[] = $tableName;
        }

        return $this;
    }

    public function ensureNeededTablesArePresent(array $snippets): void
    {
        foreach($this->neededTableNames as $tableName)
        {
            if(! $this->isAtLeastOneSnippetHasNeededTable($tableName, $snippets))
            {
                throw new \LogicException("One of query parts you used needs $tableName table");
            }
        }
    }

    private function isAtLeastOneSnippetHasNeededTable($tableName, array $snippets): bool
    {
        foreach($snippets as $snippet)
        {
            if(! $snippet instanceof NeedTableAware)
            {
                throw new \LogicException('Snippet has not expected NeedTableAware type');
            }

            if($snippet->hasNeededTable($tableName))
            {
                return true;
            }
        }

        return false;
    }
}
