<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Traits;

use Puzzle\QueryBuilder\Types\TString;
use Puzzle\QueryBuilder\Types\TBool;
use Puzzle\QueryBuilder\Types\TInt;
use Puzzle\QueryBuilder\Types\TFloat;
use Puzzle\QueryBuilder\Types\TDatetime;
use Puzzle\QueryBuilder\Type;

trait TypeGuesser
{
    private function guessType(string $columnName, $value): Type
    {
        $type = new TString($columnName);

        if(is_bool($value))
        {
            $type = new TBool($columnName);
        }

        if(is_int($value))
        {
            $type = new TInt($columnName);
        }

        if(is_float($value))
        {
            $type = new TFloat($columnName);
        }

        if($value instanceof \DateTime)
        {
            $type = new TDatetime($columnName);
        }

        return $type;
    }
}
