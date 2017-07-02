<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder;

interface Condition
{
    public function toString(Escaper $escaper): string;

    public function isEmpty(): bool;
}
