<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

use Puzzle\QueryBuilder\Conditions\AbstractCondition;
use Puzzle\QueryBuilder\Escaper;
use Puzzle\QueryBuilder\Type;

class Between extends AbstractCondition
{
    protected
        $column,
        $start,
        $end;

    public function __construct(Type $column, $start, $end)
    {
        $this->column = $column;
        $this->start = $start;
        $this->end = $end;
    }

    public function toString(Escaper $escaper): string
    {
        if($this->isEmpty())
        {
            return '';
        }

        return sprintf(
            '%s BETWEEN %s AND %s',
            $this->column->getName(),
            $this->escapeValue($this->start, $escaper),
            $this->escapeValue($this->end, $escaper)
        );
    }

    public function isEmpty(): bool
    {
        $columnName = $this->column->getName();

        if(empty($columnName) || empty($this->start) || empty($this->end))
        {
            return true;
        }

        return false;
    }

    private function escapeValue($value, Escaper $escaper)
    {
        $value = $this->column->format($value);

        if($this->column->isEscapeRequired())
        {
            $value = $escaper->escape($value);
        }

        return $value;
    }
}
