<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

class IsNull extends AbstractNullComparisonCondition
{
    protected function getOperator(): string
    {
        return 'IS NULL';
    }
}
