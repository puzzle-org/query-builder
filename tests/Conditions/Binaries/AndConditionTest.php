<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Conditions\Binaries;

use Puzzle\QueryBuilder\Conditions;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Types\TString;
use Puzzle\QueryBuilder\Types\TInt;

class AndConditionTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    /**
     * @dataProvider providerTestAndCondition
     */
    public function testAndCondition($expected, $isEmpty, $conditionA, $conditionB)
    {
        $condition = new AndCondition($conditionA, $conditionB);

        $this->assertSame($expected, $condition->toString($this->escaper));
        $this->assertSame($isEmpty, $condition->isEmpty());
    }

    public function providerTestAndCondition()
    {
        $emptyCondition = new Conditions\NullCondition();
        $conditionA = new Conditions\Equal(new TString('name'), 'rainbow');
        $conditionB = new Conditions\Equal(new TString('taste'), 'burger');
        $conditionC = new Conditions\Equal(new TInt('rank'), '42');
        $conditionD = new Conditions\Equal(new TString('author'), 'julian');

        $orComposite1  = new OrCondition($conditionA, $conditionB);
        $orComposite2  = new OrCondition($conditionC, $conditionD);

        $andComposite1 = new AndCondition($conditionA, $conditionB);
        $andComposite2 = new AndCondition($conditionC, $conditionD);

        return array(
            'simple + simple' => array("name = 'rainbow' AND taste = 'burger'", false, $conditionA, $conditionB),
            'simple + empty'  => array("name = 'rainbow'", false, $conditionA, $emptyCondition),
            'empty + empty'   => array('', true, $emptyCondition, $emptyCondition),

            'AndComposite && empty'        => array("name = 'rainbow' AND taste = 'burger'", false, $andComposite1, $emptyCondition),
            'AndComposite && condition'    => array("(name = 'rainbow' AND taste = 'burger') AND rank = 42", false, $andComposite1, $conditionC),
            'AndComposite && AndComposite' => array("(name = 'rainbow' AND taste = 'burger') AND (rank = 42 AND author = 'julian')", false, $andComposite1, $andComposite2),

            'OrComposite && empty'       => array("name = 'rainbow' OR taste = 'burger'", false, $orComposite1, $emptyCondition),
            'OrComposite && condition'   => array("(name = 'rainbow' OR taste = 'burger') AND rank = 42", false, $orComposite1, $conditionC),
            'OrComposite && OrComposite' => array("(name = 'rainbow' OR taste = 'burger') AND (rank = 42 OR author = 'julian')", false, $orComposite1, $orComposite2),
        );
    }
}