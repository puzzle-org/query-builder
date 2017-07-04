<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Collections;

use Puzzle\QueryBuilder\Queries\Snippets\SelectExpressionArgument;

interface SelectExpressionCollection extends \IteratorAggregate, \Countable, SelectExpressionArgument
{
    public function add(SelectExpressionArgument $column): self;
}
