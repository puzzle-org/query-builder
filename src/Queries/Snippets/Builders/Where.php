<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets\Builders;

use Puzzle\QueryBuilder\Condition;
use Puzzle\QueryBuilder\Escaper;

trait Where
{
    protected
        $where;

    public function where(Condition $condition): self
    {
        $this->where->where($condition);

        return $this;
    }

    private function buildWhere(Escaper $escaper): string
    {
        $this->where->setEscaper($escaper);

        return $this->where->toString();
    }
}
