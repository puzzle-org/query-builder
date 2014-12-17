<?php

namespace Mdd\QueryBuilder\Conditions;

use Mdd\QueryBuilder\Escaper;

class In extends AbstractInCondition
{
    protected function getOperator()
    {
        return 'IN';
    }
}
