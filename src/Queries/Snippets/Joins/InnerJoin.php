<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets\Joins;

use Puzzle\QueryBuilder\Snippet;

class InnerJoin extends AbstractJoin implements Snippet
{
    protected function getJoinDeclaration(): string
    {
        return 'INNER JOIN';
    }
}
