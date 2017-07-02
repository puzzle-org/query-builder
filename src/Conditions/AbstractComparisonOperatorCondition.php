<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

use Puzzle\QueryBuilder\Escaper;
use Puzzle\QueryBuilder\Type;

abstract class AbstractComparisonOperatorCondition extends AbstractCondition
{
    protected
        $leftOperand,
        $rightOperand;

    public function __construct(Type $leftOperand, $rightOperand)
    {
        $this->leftOperand = $leftOperand;
        $this->rightOperand = $rightOperand;
    }

    public function toString(Escaper $escaper): string
    {
        if($this->isEmpty())
        {
            return '';
        }

        return sprintf(
            '%s %s %s',
            $this->generateFieldOperand($this->leftOperand),
            $this->getConditionOperator(),
            $this->generateRightOperand($escaper)
        );
    }

    private function generateFieldOperand(Type $field): string
    {
        return $field->getName();
    }

    private function generateRightOperand(Escaper $escaper)
    {
        if($this->rightOperand instanceof Type)
        {
            return $this->generateFieldOperand($this->rightOperand);
        }

        return $this->escapeValue($this->rightOperand, $escaper);
    }

    public function isEmpty(): bool
    {
        $columnName = $this->leftOperand->getName();

        return empty($columnName);
    }

    abstract protected function getConditionOperator(): string;

    private function escapeValue($value, Escaper $escaper)
    {
        $value = $this->leftOperand->format($value);

        if($this->leftOperand->isEscapeRequired())
        {
            $value = $escaper->escape($value);
        }

        return $value;
    }
}
