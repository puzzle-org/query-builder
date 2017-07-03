<?php

namespace Puzzle\QueryBuilder;

interface QueryPartAware
{
    public function add(QueryPart $queryPart): Query;
}
