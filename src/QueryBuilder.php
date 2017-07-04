<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder;

use Puzzle\QueryBuilder\Queries\Delete;
use Puzzle\QueryBuilder\Queries\Insert;
use Puzzle\QueryBuilder\Queries\Select;
use Puzzle\QueryBuilder\Queries\Update;
use Puzzle\QueryBuilder\Queries\Snippets;
use Puzzle\QueryBuilder\ValueObjects\Column;
use Puzzle\QueryBuilder\Queries\Snippets\CountExpression;

class QueryBuilder
{
    use Traits\EscaperAware;

    public function delete(?string $table = null, ?string $alias = null): Delete
    {
        return (new Delete($table, $alias))->setEscaper($this->escaper);
    }

    public function insert(?string $table = null): Insert
    {
        return (new Insert($table))->setEscaper($this->escaper);
    }

    /**
     * @param string|array $columns
     */
    public function select($columns = null): Select
    {
        return (new Select($columns))->setEscaper($this->escaper);
    }

    public function update(?string $table = null, ?string $alias = null): Update
    {
        return (new Update($table, $alias))->setEscaper($this->escaper);
    }

    /**
     * @param CountExpression|string $columnName
     */
    public function count($countExpr, ?string $alias = null): Snippets\Count
    {
        if(! $countExpr instanceof CountExpression)
        {
            $countExpr = new Column($countExpr);
        }
        
        return (new Snippets\Count($countExpr, $alias));
    }

    public function distinct(string $columnName): Snippets\Distinct
    {
        return (new Snippets\Distinct(new Column($columnName)));
    }
}
