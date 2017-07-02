<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Escapers;

use Puzzle\QueryBuilder\Escaper;

class AlwaysQuoteEscaper implements Escaper
{
    public function escape($value)
    {
        return sprintf("'%s'", $value);
    }
}