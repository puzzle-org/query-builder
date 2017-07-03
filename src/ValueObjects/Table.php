<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\ValueObjects;

use Puzzle\Pieces\ConvertibleToString;

/**
 * @valueObject
 */
final class Table implements ConvertibleToString
{
    private
        $name,
        $alias;
        
    public function __construct(string $name, ?string $alias = null)
    {
        if(empty($name))
        {
            throw new \InvalidArgumentException("Empty table name");
        }
        
        $this->name = $name;
        $this->alias = $alias;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getAlias(): ?string
    {
        return $this->alias;
    }
    
    public function __toString(): string
    {
        if(empty($this->alias))
        {
            return $this->name;
        }
    
        return sprintf('%s AS %s', $this->name, $this->alias);
    }
}
