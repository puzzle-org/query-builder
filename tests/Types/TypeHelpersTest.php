<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Types;

use Puzzle\QueryBuilder\Conditions;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Condition;

class TypeHelpersTest extends TestCase
{
    /**
     * @dataProvider providerTestHelper
     */
    public function testHelper(Condition $explicit, Condition $helper)
    {
        $escaper = new AlwaysQuoteEscaper();

        $this->assertEquals(
            $explicit->toString($escaper),
            $helper->toString($escaper)
        );
    }

    public function providerTestHelper()
    {
        $field = new TString('burger');

        return [
            'equal' => [
                new Conditions\Equal($field, 'poney'),
                $field->equal('poney'),
            ],
            'different' => [
                new Conditions\Different($field, 'poney'),
                $field->different('poney'),
            ],
            'like' => [
                new Conditions\Like($field, 'poney'),
                $field->like('poney'),
            ],
            'not like' => [
                new Conditions\NotLike($field, 'poney'),
                $field->notLike('poney'),
            ],
            'greater' => [
                new Conditions\Greater($field, 'poney'),
                $field->greaterThan('poney'),
            ],
            'greater or equal' => [
                new Conditions\GreaterOrEqual($field, 'poney'),
                $field->greaterOrEqualThan('poney'),
            ],
            'lower' => [
                new Conditions\Lower($field, 'poney'),
                $field->lowerThan('poney'),
            ],
            'lower or equal' => [
                new Conditions\LowerOrEqual($field, 'poney'),
                $field->lowerOrEqualThan('poney'),
            ],
            'between' => [
                new Conditions\Between($field, 42, 666),
                $field->between(42, 666),
            ],
            'is null' => [
                new Conditions\IsNull($field),
                $field->isNull(),
            ],
            'is not null' => [
                new Conditions\IsNotNull($field),
                $field->isNotNull(),
            ],
            'in' => [
                new Conditions\In($field, array('poney', 'unicorn')),
                $field->in(array('poney', 'unicorn')),
            ],
            'not in' => [
                new Conditions\NotIn($field, array('poney', 'unicorn')),
                $field->notIn(array('poney', 'unicorn')),
            ],
        ];

    }
}