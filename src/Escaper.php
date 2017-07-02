<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder;

interface Escaper
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function escape($value);
}
