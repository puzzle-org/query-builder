<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries;

use Puzzle\QueryBuilder\Query;
use Puzzle\QueryBuilder\Traits\EscaperAware;

class Insert implements Query
{
    use EscaperAware;

    private
        $insertPart,
        $valuesPart;

    public function __construct(?string $table = null)
    {
        $this->valuesPart = new Snippets\Values();

        if(! empty($table))
        {
            $this->insert($table);
        }
    }

    public function toString(): string
    {
        $queryParts = array(
            $this->buildInsertString(),
            $this->buildValuesString(),
        );

        return implode(' ', $queryParts);
    }

    public function insert(?string $table): self
    {
        $this->insertPart = new Snippets\TableName($table);

        return $this;
    }

    public function values(array $values): self
    {
        $this->valuesPart->values($values);

        return $this;
    }

    private function buildInsertString(): string
    {
        return sprintf('INSERT INTO %s', $this->insertPart->toString());
    }

    private function buildValuesString(): string
    {
        $this->valuesPart->setEscaper($this->escaper);

        return $this->valuesPart->toString();
    }
}
