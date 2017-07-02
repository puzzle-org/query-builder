<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

class Greater extends AbstractComparisonOperatorCondition
{
    protected function getConditionOperator(): string
    {
        return '>';
    }
}
