<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

class NotLike extends AbstractComparisonOperatorCondition
{
    protected function getConditionOperator(): string
    {
        return 'NOT LIKE';
    }
}
