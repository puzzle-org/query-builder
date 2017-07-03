<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\ValueObjects;

/**
 * @valueObject
 */
final class Column
{
    private
        $name;
        
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }
}
