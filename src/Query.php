<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder;

interface Query
{
    public function toString(): string;
}
