<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder;

interface Type
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function format($value);

    public function isEscapeRequired(): bool;

    public function getName(): string;
}
