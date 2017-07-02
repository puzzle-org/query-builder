<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions\Sets;

use Puzzle\QueryBuilder\Conditions\AbstractCondition;
use Puzzle\QueryBuilder\Conditions\CompositeCondition;
use Puzzle\QueryBuilder\Escaper;
use Puzzle\QueryBuilder\Condition;

abstract class AbstractSet extends AbstractCondition implements CompositeCondition
{
    private
    $conditions;

    public function __construct()
    {
        $this->conditions = array();
    }

    public function toString(Escaper $escaper): string
    {
        if($this->isEmpty())
        {
            return '';
        }

        $condition = $this->buildCompositeCondition();

        return $condition->toString($escaper);
    }

    public function add(Condition $condition): self
    {
        $this->conditions[] = $condition;

        return $this;
    }

    public function isEmpty(): bool
    {
        foreach($this->conditions as $condition)
        {
            if(! $condition->isEmpty())
            {
                return false;
            }
        }

        return true;
    }

    private function buildCompositeCondition(): Condition
    {
        $conditions = $this->getNotEmptyConditions();

        $compositeCondition = array_shift($conditions);

        foreach($conditions as $condition)
        {
            $compositeCondition = $this->joinConditions($compositeCondition, $condition);
        }

        return $compositeCondition;
    }

    private function getNotEmptyConditions(): array
    {
        return array_filter($this->conditions, function (Condition $item) {
            return $item->isEmpty() === false;
        });
    }

    abstract protected function joinConditions(Condition $leftCondition, Condition $rightCondition): Condition;
}
