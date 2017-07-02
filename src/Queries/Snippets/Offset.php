<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;
use Puzzle\Pieces\StringManipulation;

class Offset implements Snippet
{
    use StringManipulation;

    private
        $offset;

    /**
     * @param int|string $offset
     */
    public function __construct($offset)
    {
        $this->offset = $this->convertToInteger($offset);
    }

    public function toString(): string
    {
        if(is_null($this->offset))
        {
            return '';
        }

        return sprintf(
            'OFFSET %d',
            $this->offset
        );
    }

    private function convertToInteger($value): ?int
    {
        if($this->isConvertibleToString($value) === false)
        {
            throw new \InvalidArgumentException("Offset argument must be an integer or an integer wrapped into a string");
        }

        if(preg_match('~^[\d]+$~', (string) $value))
        {
            return (int) $value;
        }

        return null;
    }
}
