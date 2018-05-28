<?php

namespace Tuck\Sort\Tests;

use DateTimeImmutable;
use Tuck\Sort\Compare;
use PHPUnit\Framework\TestCase;

class CompareTest extends TestCase
{
    public function testLooselyComparingValues()
    {
        $this->assertEquals(-1, Compare::loose(1, 2));
        $this->assertEquals(0, Compare::loose(1, 1));
        $this->assertEquals(1, Compare::loose(2, 1));
    }

    public function testStrictlyComparingValues()
    {
        $this->assertEquals(-1, Compare::strict(1, 2));
        $this->assertEquals(0, Compare::strict(1, 1));
        $this->assertEquals(1, Compare::strict(2, 1));
    }

    public function testLooselyWillCoerceValues()
    {
        $this->assertEquals(-1, Compare::loose(1, 2.0));
        $this->assertEquals(0, Compare::loose(1, 1.0));
        $this->assertEquals(1, Compare::loose(2, 1.0));
    }

    public function testStrictOnlySeesEqualityOnSameInstance()
    {
        $x = new DateTimeImmutable('June 21, 2015');
        $y = new DateTimeImmutable('June 21, 2015');

        $this->assertEquals(0, Compare::loose($x, $y));
        $this->assertEquals(1, Compare::strict($x, $y));
    }
}
