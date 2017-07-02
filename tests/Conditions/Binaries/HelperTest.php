<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions\Binaries;

use Puzzle\QueryBuilder\Conditions;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Types\TString;
use Puzzle\QueryBuilder\Types\TInt;

class HelperTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    public function testMixedHelper()
    {
        $condition = (new Conditions\Equal(new TString('name'), 'rainbow'))
            ->or(
                (new Conditions\Equal(new TString('taste'), 'burger'))
                ->and(new Conditions\Equal(new TInt('rank'), 42))
            );

        $this->assertSame($condition->toString($this->escaper), "name = 'rainbow' OR (taste = 'burger' AND rank = 42)");
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testNoParameterGiven()
    {
        $condition = new Conditions\Equal(new TString('taste'), 'burger');
        $condition->or();
    }

    /**
     * @expectedException \LogicException
     */
    public function testTypoInOrHelperName()
    {
        $condition = new Conditions\Equal(new TString('taste'), 'burger');
        $condition->ro(new Conditions\Equal(new TString('taste'), 'vegetable'));
    }

    /**
     * @expectedException \LogicException
     */
    public function testTypoInAndHelperName()
    {
        $condition = new Conditions\Equal(new TString('taste'), 'burger');
        $condition->adn(new Conditions\Equal(new TString('taste'), 'vegetable'));
    }
}