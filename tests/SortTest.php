<?php

namespace Tuck\Sort\Tests;

use ArrayIterator;
use DateTime;
use Tuck\Sort\Compare;
use Tuck\Sort\Sort;

class SortTest extends \PHPUnit_Framework_TestCase
{
    public function testSortingByValues()
    {
        $list = [3, 2, 4, 1];

        $this->assertEquals([1, 2, 3, 4], Sort::values($list));
        $this->assertEquals([3, 2, 4, 1], $list, 'Original list should not have been modified');
    }

    public function testPreservingKeysWhenSortingByValue()
    {
        $list = ['a' => 'raspberry', 'b' => 'blueberry', 'c' => 'lemon'];

        $this->assertEquals(
            ['b' => 'blueberry', 'c' => 'lemon', 'a' => 'raspberry'],
            Sort::values($list, Sort::PRESERVE_KEYS)
        );
    }

    public function testKeys()
    {
        $list = ['c' => 'blat', 'a' => 'foo', 'b' => 'bar'];

        $this->assertEquals(['a' => 'foo', 'b' => 'bar', 'c' => 'blat'], Sort::keys($list));
        $this->assertEquals(['c' => 'blat', 'a' => 'foo', 'b' => 'bar'], $list, 'Original list should not have been modified');
    }

    public function testNaturalSorting()
    {
        $this->assertEquals(
            ["img1.png", "img2.png", "img10.png", "img12.png"],
            Sort::natural(["img12.png", "img10.png", "img2.png", "img1.png"])
        );
    }

    public function testPreservingKeysWithNaturalSorting()
    {
        $this->assertEquals(
            [3 => "img1.png", 2 => "img2.png", 1 => "img10.png", 0 => "img12.png"],
            Sort::natural(["img12.png", "img10.png", "img2.png", "img1.png"], Sort::PRESERVE_KEYS)
        );
    }

    public function testSortingCollection()
    {
        $x = new ArrayIterator([3, 2, 5, 1, 4]);

        $this->assertEquals([1, 2, 3, 4, 5], Sort::values($x));
    }

    public function testSortingGenerator()
    {
        $generator = function () {
            yield 2;
            yield 1;
            yield 3;
        };

        $this->assertEquals([1, 2, 3], Sort::values($generator()));
    }

    public function testSortingByCallback()
    {
        $x = ['derp', 'so', 'foods', 'dat'];

        $comparison = function ($a, $b) {
            return Compare::loose(strlen($a), strlen($b));
        };

        $this->assertEquals(
            ['so', 'dat', 'derp', 'foods'],
            Sort::user($x, $comparison),
            true
        );
    }

    public function testPreservingKeysWhenSortingByCallback()
    {
        $x = ['derp', 'so', 'foods', 'dat'];

        $comparison = function ($a, $b) {
            return Compare::loose(strlen($a), strlen($b));
        };

        $this->assertEquals(
            [1 => 'so', 3 => 'dat', 0 => 'derp', 2 => 'foods'],
            Sort::user($x, $comparison, Sort::PRESERVE_KEYS),
            true
        );
    }

    public function testSortByShorthand()
    {
        $list = [
            $aisha = new HighScore('Aisha', 3000, new DateTime('June 21, 2015')),
            $ross = new HighScore('Ross', 1000, new DateTime('June 22, 2015')),
            $steven = new HighScore('Steven', 2000, new DateTime('June 21, 2015')),
        ];

        $this->assertEquals(
            [$ross, $steven, $aisha],
            Sort::by(
                $list,
                function (HighScore $score) {
                    return $score->getScore();
                }
            )
        );
    }

    public function testSortReversedBy()
    {
        $list = [
            $aisha = new HighScore('Aisha', 3000, new DateTime('June 21, 2015')),
            $ross = new HighScore('Ross', 1000, new DateTime('June 22, 2015')),
            $steven = new HighScore('Steven', 2000, new DateTime('June 21, 2015')),
        ];

        $this->assertEquals(
            [$aisha, $steven, $ross],
            Sort::reversedBy(
                $list,
                function (HighScore $score) {
                    return $score->getScore();
                }
            )
        );
    }
}
