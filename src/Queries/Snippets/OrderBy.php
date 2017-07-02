<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Snippet;

class OrderBy implements Snippet
{
    const
        ASC = 'ASC',
        DESC = 'DESC';

    private
        $orders;

    public function __construct()
    {
        $this->orders = array();
    }

    public function addOrderBy(string $column, string $direction = self::ASC): void
    {
        $this->validateDirection($direction);

        $this->orders[$column] = $direction;
    }

    public function toString(): string
    {
        $orders = [];

        foreach($this->orders as $column => $direction)
        {
            if(! empty($column))
            {
                $orders[] = $column . ' ' . $direction;
            }
        }

        if(empty($orders))
        {
            return '';
        }

        return sprintf('ORDER BY %s', implode(', ', $orders));
    }

    private function validateDirection(string $direction): void
    {
        $availableDirections = [self::ASC, self::DESC];

        if(! in_array($direction, $availableDirections))
        {
            throw new \InvalidArgumentException(sprintf('Unsupported ORDER BY direction "%s"', $direction));
        }
    }
}
