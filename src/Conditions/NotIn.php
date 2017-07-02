<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

class NotIn extends AbstractInCondition
{
    protected function getOperator(): string
    {
        return 'NOT IN';
    }
}
