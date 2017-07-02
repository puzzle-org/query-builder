<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;
use Puzzle\QueryBuilder\Condition;
use Puzzle\QueryBuilder\Conditions;
use Puzzle\QueryBuilder\Traits\EscaperAware;
use Puzzle\QueryBuilder\Escaper;

class Having implements Snippet
{
    use
        EscaperAware;

    private
        $condition;

    public function __construct()
    {
        $this->condition = new Conditions\NullCondition();
    }

    public function having(Condition $condition): self
    {
        $this->addCondition($condition);

        return $this;
    }

    public function toString(): string
    {
        if($this->condition->isEmpty())
        {
            return '';
        }

        return sprintf('HAVING %s', $this->condition->toString($this->escaper));
    }

    private function addCondition(Condition $condition): void
    {
        $this->condition = $this->condition->and($condition);
    }
}
