<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;
use Puzzle\QueryBuilder\Type;
use Puzzle\QueryBuilder\Conditions;
use Puzzle\QueryBuilder\Traits\EscaperAware;
use Puzzle\QueryBuilder\Traits\TypeGuesser;

class Set implements Snippet
{
    use
        EscaperAware,
        TypeGuesser;

    private
        $sets;

    public function __construct()
    {
        $this->sets = [];
    }

    public function set(array $fields): self
    {
        $this->sets = array_merge($this->sets, $fields);

        return $this;
    }

    public function toString(): string
    {
        if(empty($this->sets))
        {
            return '';
        }

        $sets = array();
        foreach($this->sets as $columnName => $value)
        {
            $type = $this->guessType($columnName, $value);

            $sets[] = (new Conditions\Equal($type, $value))->toString($this->escaper);
        }

        return sprintf('SET %s', implode(', ', $sets));
    }
}
