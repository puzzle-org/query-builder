<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Types;

class TDatetime extends AbstractType
{
    const
        MYSQL_DATETIME_FORMAT = 'Y-m-d H:i:s';

    public function isEscapeRequired(): bool
    {
        return true;
    }

    public function format($value)
    {
        if($value instanceof \DateTime)
        {
            return $value->format(self::MYSQL_DATETIME_FORMAT);
        }

        return (string) $value;
    }
}
