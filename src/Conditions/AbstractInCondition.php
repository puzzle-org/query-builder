<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

use Puzzle\QueryBuilder\Escaper;
use Puzzle\QueryBuilder\Type;

abstract class AbstractInCondition extends AbstractCondition
{
    protected
        $type,
        $values;

    public function __construct(Type $column, array $values)
    {
        $this->type = $column;
        $this->values = $values;
    }

    public function toString(Escaper $escaper): string
    {
        if($this->isEmpty())
        {
            return '';
        }

        $values = $this->escapeValues($this->values, $escaper);

        return sprintf(
            '%s %s (%s)',
            $this->type->getName(),
            $this->getOperator(),
            implode(', ', $values)
        );
    }

    public function isEmpty(): bool
    {
        $columnName = $this->type->getName();

        return empty($columnName);
    }

    abstract protected function getOperator(): string;

    protected function escapeValues(array $values, Escaper $escaper): array
    {
        $escapedValues = [];

        foreach($values as $value)
        {
            $formatedValue = $this->type->format($value);
            if($this->type->isEscapeRequired())
            {
                $formatedValue = $escaper->escape($formatedValue);
            }

            $escapedValues[] = $formatedValue;
        }

        return $escapedValues;
    }
}
