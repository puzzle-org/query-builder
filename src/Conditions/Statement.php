<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

use Puzzle\QueryBuilder\Escaper;
use Puzzle\QueryBuilder\Query;

class Statement extends AbstractCondition
{
    private
        $statement;

    public function __construct($statement)
    {
        $this->statement = $statement;
    }

    public function toString(Escaper $escaper): string
    {
        if($this->isEmpty())
        {
            return '';
        }

        $statement = $this->statement;

        if($this->statement instanceof Query)
        {
            $this->statement->setEscaper($escaper);

            $statement = $this->wrapWithParenthesis($this->statement->toString());
        }

        return (string) $statement;
    }

    private function wrapWithParenthesis(string $value): string
    {
        return sprintf('(%s)', $value);
    }

    public function isEmpty(): bool
    {
        return empty($this->statement);
    }
}
