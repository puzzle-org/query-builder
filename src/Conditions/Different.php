<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

class Different extends AbstractComparisonOperatorCondition
{
    protected function getConditionOperator(): string
    {
        return '!=';
    }
}
