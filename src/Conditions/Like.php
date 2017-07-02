<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

class Like extends AbstractComparisonOperatorCondition
{
    protected function getConditionOperator(): string
    {
        return 'LIKE';
    }
}
