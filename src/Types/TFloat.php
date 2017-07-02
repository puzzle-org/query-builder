<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Types;

class TFloat extends AbstractType
{
    public function isEscapeRequired(): bool
    {
        return false;
    }

    public function format($value)
    {
        return floatval($value);
    }
}
