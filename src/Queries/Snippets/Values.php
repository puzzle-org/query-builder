<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;
use Puzzle\QueryBuilder\Type;
use Puzzle\QueryBuilder\Traits\EscaperAware;
use Puzzle\QueryBuilder\Traits\TypeGuesser;

class Values implements Snippet
{
    use
        EscaperAware,
        TypeGuesser;

    private
        $values;

    public function __construct(array $values = null)
    {
        $this->values = [];

        if(! empty($values))
        {
            $this->values($values);
        }
    }

    public function values(array $values): self
    {
        $this->values[] = $values;

        return $this;
    }

    public function toString(): string
    {
        if(empty($this->values))
        {
            throw new \RuntimeException('No values to insert');
        }

        $columnsNameList = array_filter(array_keys(reset($this->values)));

        $values = [];

        foreach($this->values as $valuesSet)
        {
            if($columnsNameList !== array_keys($valuesSet))
            {
                throw new \RuntimeException('Cannot insert different schema on the same table.');
            }

            $values[] = $this->buildValuesSetString($valuesSet);
        }

        return sprintf(
            '%s VALUES %s',
            $this->wrapWithParenthesis(implode(', ', $columnsNameList)),
            implode(', ', $values)
        );
    }

    private function buildValuesSetString(array $values): string
    {
        $valuesSet = [];

        foreach($values as $columnName => $value)
        {
            if(! empty($columnName))
            {
                $type = $this->guessType($columnName, $value);

                $valuesSet[] = $this->formatValue($type, $value);
            }
        }

        return $this->wrapWithParenthesis(implode(', ', $valuesSet));
    }

    private function formatValue(Type $type, $value)
    {
        if(is_null($value))
        {
            return 'NULL';
        }

        return $this->escapeValue($type, $value);
    }

    private function escapeValue(Type $type, $value)
    {
        $value = $type->format($value);

        if($type->isEscapeRequired())
        {
            $value = $this->escaper->escape($value);
        }

        return $value;
    }

    private function wrapWithParenthesis(string $value): string
    {
        return sprintf('(%s)', $value);
    }
}
