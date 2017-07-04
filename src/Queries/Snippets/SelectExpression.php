<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;

interface SelectExpression extends Snippet, SelectExpressionArgument
{
    public function equals(SelectExpression $expr): bool;
}
