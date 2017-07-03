<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder;

interface QueryPart
{
    public function build(QueryPartAware $query): void;
}
