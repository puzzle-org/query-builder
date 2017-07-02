<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

class IsNotNull extends AbstractNullComparisonCondition
{
    protected function getOperator(): string
    {
        return 'IS NOT NULL';
    }
}
