<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;
use Puzzle\Pieces\StringManipulation;

class Limit implements Snippet
{
    use StringManipulation;

    private
        $limit;

    /**
     * @param int|string $limit
     */
    public function __construct($limit)
    {
        $this->limit = $this->convertToInteger($limit);
    }

    public function toString(): string
    {
        if(is_null($this->limit))
        {
            return '';
        }

        return sprintf(
            'LIMIT %d',
            $this->limit
        );
    }

    private function convertToInteger($value): ?int
    {
        if($this->isConvertibleToString($value) === false)
        {
            throw new \InvalidArgumentException("Limit argument must be an integer or an integer wrapped into a string");
        }

        if(preg_match('~^[\d]+$~', (string) $value))
        {
            return (int) $value;
        }

        return null;
    }
}
