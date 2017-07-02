<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;
use Puzzle\QueryBuilder\Condition;
use Puzzle\QueryBuilder\Traits\EscaperAware;

class Where implements Snippet
{
    use EscaperAware;

    private
        $conditions;

    public function __construct(Condition $condition = null)
    {
        $this->conditions = array();

        if($condition instanceof Condition)
        {
            $this->addCondition($condition);
        }
    }

    public function where(Condition $condition): self
    {
        $this->addCondition($condition);

        return $this;
    }

    public function toString(): string
    {
        $conditionString = $this->buildConditionString();
        if(empty($conditionString))
        {
            return '';
        }

        return sprintf('WHERE %s', $conditionString);
    }

    private function buildConditionString(): string
    {
        $whereConditions = array();
        foreach($this->conditions as $condition)
        {
            $conditionString = $condition->toString($this->escaper);
            if(! empty($conditionString))
            {
                $whereConditions[] = $conditionString;
            }
        }

        return implode(' AND ', $whereConditions);
    }

    private function addCondition(Condition $condition): void
    {
        $this->conditions[] = $condition;
    }
}
