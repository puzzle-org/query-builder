<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Collections;

use Puzzle\QueryBuilder\ValueObjects\Column;

interface ColumnCollection extends \IteratorAggregate, \Countable
{
    public function add(Column $column): self;
    
    public function mergeWith(self $collection): void;
}
