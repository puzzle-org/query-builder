<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets\Builders;

use Puzzle\QueryBuilder\Queries\Snippets;

trait Limit
{
    protected
        $limit,
        $offset;

    /**
     * @param int|string $limit
     */
    public function limit($limit): self
    {
        $this->limit = new Snippets\Limit($limit);

        return $this;
    }

    /**
     * @param int|string $offset
     */
    public function offset($offset): self
    {
        if(!$this->limit instanceof Snippets\Limit)
        {
            throw new \LogicException('LIMIT is required to define OFFSET.');
        }

        $this->offset = new Snippets\Offset($offset);

        return $this;
    }

    private function buildLimit(): string
    {
        $limit = $this->buildLimitClause();

        $offset = '';
        if(! empty($limit))
        {
            $offset = $this->buildOffsetClause();
        }

        $clauses = array($limit, $offset);

        return implode(' ', array_filter($clauses));
    }

    private function buildLimitClause(): string
    {
        if($this->limit instanceof Snippets\Limit)
        {
            return $this->limit->toString();
        }

        return '';
    }

    private function buildOffsetClause(): string
    {
        if($this->offset instanceof Snippets\Offset)
        {
            return $this->offset->toString();
        }

        return '';
    }
}
