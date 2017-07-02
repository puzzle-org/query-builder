<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions\Sets;

use Puzzle\QueryBuilder\Condition;

class OrSet extends AbstractSet
{
    protected function joinConditions(Condition $leftCondition, Condition $rightCondition): Condition
    {
        // FIXME or is not defined in Condition interface (it works only if the Condition impl extends AbstractCondition)
        return $leftCondition->or($rightCondition);
    }
}
