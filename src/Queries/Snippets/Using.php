<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;
use Puzzle\Pieces\StringManipulation;

class Using implements Snippet
{
    use StringManipulation;

    private
        $columns;

    /**
     * @param array[string]|string $column
     */
    public function __construct($column)
    {
        $this->columns = $this->convertToArray($column);
    }

    public function toString(): string
    {
        $usingColumns = implode(', ', array_filter($this->columns));

        if(empty($usingColumns))
        {
            return '';
        }

        return sprintf(
            'USING (%s)',
            $usingColumns
        );
    }

    private function convertToArray($input): array
    {
        if(! is_array($input))
        {
            if(! $this->isConvertibleToString($input))
            {
                throw new \InvalidArgumentException("Using argument must be a string (or an array of string)");
            }

            $input = array($input);
        }

        return $input;
    }
}
