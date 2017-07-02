<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Types;

use Puzzle\QueryBuilder\Type;
use Puzzle\QueryBuilder\Conditions;
use Puzzle\QueryBuilder\Conditions\Equal;
use Puzzle\QueryBuilder\Conditions\Different;
use Puzzle\QueryBuilder\Conditions\Like;
use Puzzle\QueryBuilder\Conditions\NotLike;
use Puzzle\QueryBuilder\Conditions\Greater;
use Puzzle\QueryBuilder\Conditions\GreaterOrEqual;
use Puzzle\QueryBuilder\Conditions\Lower;
use Puzzle\QueryBuilder\Conditions\LowerOrEqual;
use Puzzle\QueryBuilder\Conditions\Between;
use Puzzle\QueryBuilder\Conditions\In;
use Puzzle\QueryBuilder\Conditions\IsNull;
use Puzzle\QueryBuilder\Conditions\IsNotNull;
use Puzzle\QueryBuilder\Conditions\NotIn;

abstract class AbstractType implements Type
{
    private
        $name;

    public function __construct(string $name)
    {
        $this->name = (string) $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function equal($value): Conditions\Equal
    {
        return new Equal($this, $value);
    }

    public function different($value): Conditions\Different
    {
        return new Different($this, $value);
    }

    public function like($value): Conditions\Like
    {
        return new Like($this, $value);
    }

    public function notLike($value): Conditions\NotLike
    {
        return new NotLike($this, $value);
    }

    public function greaterThan($value): Conditions\Greater
    {
        return new Greater($this, $value);
    }

    public function greaterOrEqualThan($value): Conditions\GreaterOrEqual
    {
        return new GreaterOrEqual($this, $value);
    }

    public function lowerThan($value): Conditions\Lower
    {
        return new Lower($this, $value);
    }

    public function lowerOrEqualThan($value): Conditions\LowerOrEqual
    {
        return new LowerOrEqual($this, $value);
    }

    public function between($start, $end): Conditions\Between
    {
        return new Between($this, $start, $end);
    }

    public function in(array $values): Conditions\In
    {
        return new In($this, $values);
    }

    public function isNull(): Conditions\IsNull
    {
        return new IsNull($this);
    }

    public function isNotNull(): Conditions\IsNotNull
    {
        return new IsNotNull($this);
    }

    public function notIn(array $values): Conditions\NotIn
    {
        return new NotIn($this, $values);
    }
}
