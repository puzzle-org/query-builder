<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

use Puzzle\QueryBuilder\Condition;
use Puzzle\QueryBuilder\Conditions\Binaries\AndCondition;
use Puzzle\QueryBuilder\Conditions\Binaries\OrCondition;

abstract class AbstractCondition implements Condition
{
    public function _and(Condition $condition): AndCondition
    {
        return new AndCondition($this, $condition);
    }

    public function _or(Condition $condition): OrCondition
    {
        return new OrCondition($this, $condition);
    }

    public function __call(string $methodName, $arguments): Condition
    {
        $method = '_' . $methodName;

        if(method_exists($this, $method))
        {
            if(array_key_exists(0, $arguments))
            {
                return $this->$method($arguments[0]);
            }

            throw new \RuntimeException(sprintf("Missing parameter 1 for %s", $method));
        }

        throw new \LogicException(sprintf("Unkown method %s", $method));
    }
}
