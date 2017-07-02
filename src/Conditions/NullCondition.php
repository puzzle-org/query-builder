<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

use Puzzle\QueryBuilder\Escaper;

class NullCondition extends AbstractCondition
{
    public function toString(Escaper $escaper): string
    {
        return '';
    }

    public function isEmpty(): bool
    {
        return true;
    }
}
