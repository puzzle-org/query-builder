<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Queries;

use Puzzle\QueryBuilder\Escapers\AlwaysQuoteEscaper;
use PHPUnit\Framework\TestCase;

class InsertTest extends TestCase
{
    protected
        $escaper;

    protected function setUp()
    {
        $this->escaper = new AlwaysQuoteEscaper();
    }

    public function testInsertUsingConstructor()
    {
        $query = (new Insert('burger'))->setEscaper($this->escaper);

        $query->values(array(
            'id' => 666,
            'name' => 'poney',
            'date' => \Datetime::createFromFormat('Y-m-d H:i:s', '2017-03-07 13:37:42'),
            'score' => 13.37,
        ));

        $this->assertSame("INSERT INTO burger (id, name, date, score) VALUES (666, 'poney', '2017-03-07 13:37:42', 13.37)", $query->toString($this->escaper));

        $query->values(array(
            'id' => '0667',
            'name' => 'unicorn',
            'date' => \Datetime::createFromFormat('Y-m-d H:i:s', '2017-03-07 13:42:59'),
            'score' => 14.18,
        ));

        $this->assertSame("INSERT INTO burger (id, name, date, score) VALUES (666, 'poney', '2017-03-07 13:37:42', 13.37), ('0667', 'unicorn', '2017-03-07 13:42:59', 14.18)", $query->toString($this->escaper));
    }

    public function testInsertUsingHelper()
    {
        $query = (new Insert())->setEscaper($this->escaper);

        $query
            ->insert('burger')
            ->values(array(
                'id' => 666,
                'name' => 'poney',
                'date' => \Datetime::createFromFormat('Y-m-d H:i:s', '2017-03-07 13:37:42'),
                'score' => 13.37,
            ));

        $this->assertSame("INSERT INTO burger (id, name, date, score) VALUES (666, 'poney', '2017-03-07 13:37:42', 13.37)", $query->toString($this->escaper));
    }
}
