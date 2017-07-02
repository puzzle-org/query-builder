<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions\Binaries;

use Puzzle\QueryBuilder\Escaper;

class OrCondition extends AbstractCompositeCondition
{
    protected function buildCondition(Escaper $escaper): string
    {
        return sprintf(
            '%s OR %s',
            $this->buildPartCondition($this->leftCondition, $escaper),
            $this->buildPartCondition($this->rightCondition, $escaper)
        );
    }
}
