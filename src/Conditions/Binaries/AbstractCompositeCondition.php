<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions\Binaries;

use Puzzle\QueryBuilder\Conditions\CompositeCondition;
use Puzzle\QueryBuilder\Condition;
use Puzzle\QueryBuilder\Escaper;
use Puzzle\QueryBuilder\Conditions\AbstractCondition;

abstract class AbstractCompositeCondition extends AbstractCondition implements CompositeCondition
{
    protected
        $leftCondition,
        $rightCondition;

    public function __construct(Condition $leftCondition, Condition $rightCondition)
    {
        $this->leftCondition = $leftCondition;
        $this->rightCondition = $rightCondition;
    }

    public function toString(Escaper $escaper): string
    {
        if($this->leftCondition->isEmpty())
        {
            return $this->rightCondition->toString($escaper);
        }

        if($this->rightCondition->isEmpty())
        {
            return $this->leftCondition->toString($escaper);
        }

        return $this->buildCondition($escaper);
    }

    public function isEmpty(): bool
    {
        return $this->leftCondition->isEmpty() && $this->rightCondition->isEmpty();
    }

    protected function buildPartCondition(Condition $condition, Escaper $escaper): string
    {
        $partCondition = $condition->toString($escaper);

        if($condition instanceof CompositeCondition)
        {
            $partCondition = sprintf('(%s)', $partCondition);
        }

        return $partCondition;
    }

    abstract protected function buildCondition(Escaper $escaper): string;
}
