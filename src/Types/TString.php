<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Types;

class TString extends AbstractType
{
    public function isEscapeRequired(): bool
    {
        return true;
    }

    public function format($value)
    {
        return (string) $value;
    }
}
