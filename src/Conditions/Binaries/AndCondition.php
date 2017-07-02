<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions\Binaries;

use Puzzle\QueryBuilder\Escaper;

class AndCondition extends AbstractCompositeCondition
{
    protected function buildCondition(Escaper $escaper): string
    {
        return sprintf(
            '%s AND %s',
            $this->buildPartCondition($this->leftCondition, $escaper),
            $this->buildPartCondition($this->rightCondition, $escaper)
        );
    }
}
