<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions;

use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Types\TInt;

class NullConditionTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    public function testBareNullCondition()
    {
        $condition = new NullCondition();

        $this->assertSame('', $condition->toString($this->escaper));
    }

    public function testNullConditionAndNullCondition()
    {
        $condition = (new NullCondition())->and(new NullCondition());

        $this->assertSame('', $condition->toString($this->escaper));
    }

    public function testNullConditionAndCondition()
    {
        $condition = (new NullCondition())->and(new Different(new TInt('poney'), 666));

        $this->assertSame('poney != 666', $condition->toString($this->escaper));

        $condition = (new Different(new TInt('poney'), 666))->and(new NullCondition());

        $this->assertSame('poney != 666', $condition->toString($this->escaper));
    }

    public function testNullConditionOrCondition()
    {
        $condition = (new NullCondition())->or(new Different(new TInt('poney'), 666));

        $this->assertSame('poney != 666', $condition->toString($this->escaper));

        $condition = (new Different(new TInt('poney'), 666))->or(new NullCondition());

        $this->assertSame('poney != 666', $condition->toString($this->escaper));
    }

    public function testNullConditionAndComposite()
    {
        $composite = (new Different(new TInt('poney'), 666))
                        ->and(
                            (new Equal(new TInt('response'), 42))
                                ->or(new Greater(new TInt('score'), 1337))
                        );

        $condition = (new NullCondition())->and($composite);

        $this->assertSame('poney != 666 AND (response = 42 OR score > 1337)', $condition->toString($this->escaper));

        $condition = $composite->and(new NullCondition());

        $this->assertSame('poney != 666 AND (response = 42 OR score > 1337)', $condition->toString($this->escaper));
    }

    public function testNullConditionOrComposite()
    {
        $composite = (new Different(new TInt('poney'), 666))
                        ->and(
                            (new Equal(new TInt('response'), 42))
                                ->or(new Greater(new TInt('score'), 1337))
                        );

        $condition = (new NullCondition())->or($composite);

        $this->assertSame('poney != 666 AND (response = 42 OR score > 1337)', $condition->toString($this->escaper));

        $condition = $composite->or(new NullCondition());

        $this->assertSame('poney != 666 AND (response = 42 OR score > 1337)', $condition->toString($this->escaper));
    }

    public function testConditionIsEmpty()
    {
        $condition = new NullCondition();

        $this->assertTrue($condition->isEmpty());

        $condition = (new NullCondition())->and(new Equal(new TInt('response'), 42));

        $this->assertFalse($condition->isEmpty());

        $condition = (new Equal(new TInt('response'), 42))->and(new NullCondition());

        $this->assertFalse($condition->isEmpty());
    }
}