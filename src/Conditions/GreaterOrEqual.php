<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

class GreaterOrEqual extends AbstractComparisonOperatorCondition
{
    protected function getConditionOperator(): string
    {
        return '>=';
    }
}
