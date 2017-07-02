<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets\Joins;

use Puzzle\QueryBuilder\Snippet;

class RightJoin extends AbstractJoin implements Snippet
{
    protected function getJoinDeclaration(): string
    {
        return 'RIGHT JOIN';
    }
}
