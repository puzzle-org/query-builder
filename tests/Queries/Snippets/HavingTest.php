<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries\Snippets;

use Puzzle\QueryBuilder\Conditions;
use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use PHPUnit\Framework\TestCase;
use Puzzle\QueryBuilder\Types\TString;
use Puzzle\QueryBuilder\Types\TInt;

class HavingTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    /**
     * @dataProvider providerTestHaving
     */
    public function testHaving($expected, $condition)
    {
        $qb = new Having();
        $qb->setEscaper($this->escaper);
        $qb->having($condition);

        $this->assertSame($expected, $qb->toString($this->escaper));
    }

    public function providerTestHaving()
    {
        $nullCondition = new Conditions\NullCondition();
        $simpleCondition = new Conditions\Greater(new TInt('score'), 42);

        $compositeCondition1 = $simpleCondition->and((new Conditions\Equal(new TString('type'), 'burger'))->or(new Conditions\Greater(new TInt('score'), 1337)));
        $compositeCondition2 = $nullCondition->and($simpleCondition)->and((new Conditions\Equal(new TString('type'), 'burger'))->or(new Conditions\Greater(new TInt('score'), 1337)));

        $nullAndCompositeCondition = $nullCondition->and((new Conditions\Equal(new TString('type'), 'burger'))->or(new Conditions\Greater(new TInt('score'), 1337)));
        $nullCompositeCondition = $nullCondition->and($nullCondition->and($nullCondition->or($nullCondition)));

        return array(
            'null condition' => array('', $nullCondition),
            'null composite condition' => array('', $nullCompositeCondition),

            'simple condition' => array('HAVING score > 42', $simpleCondition),
            'composite condition 1' => array("HAVING score > 42 AND (type = 'burger' OR score > 1337)", $compositeCondition1),
            'nullCondition and simple condition and composite condition 2' => array("HAVING (score > 42) AND (type = 'burger' OR score > 1337)", $compositeCondition2),
        );
    }

    public function testNullHaving()
    {
        $qb = new Having();
        $qb->setEscaper($this->escaper);

        $qb->having(new Conditions\NullCondition());

        $this->assertSame('', $qb->toString($this->escaper));
    }
}