<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

class In extends AbstractInCondition
{
    protected function getOperator(): string
    {
        return 'IN';
    }
}
