<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Types;

class TBool extends AbstractType
{
    public function isEscapeRequired(): bool
    {
        return false;
    }

    public function format($value)
    {
        return (int) ((bool) $value);
    }
}
