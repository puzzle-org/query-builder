<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

interface NeedTableAware
{
    public function hasNeededTable(string $tableName): bool;
}
