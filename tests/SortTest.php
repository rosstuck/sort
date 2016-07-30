<?php

namespace Tuck\Sort\Tests;

use ArrayIterator;
use DateTime;
use Tuck\Sort\Compare;
use Tuck\Sort\Sort;
use Tuck\Sort\Options\Casing;
use Tuck\Sort\Options\Keys;
use Tuck\Sort\Options\Order;

class SortTest extends \PHPUnit_Framework_TestCase
{
    public function testSortingByValues()
    {
        $list = [3, 2, 4, 1];

        $this->assertSame([1, 2, 3, 4], Sort::values($list));
        $this->assertSame([3, 2, 4, 1], $list, 'Original list should not have been modified');
    }

    public function testSortingByDescendingValues()
    {
        $list = [3, 2, 4, 1];
        $this->assertSame([4, 3, 2, 1], Sort::values($list, Order::descending()));
    }

    public function testSortingByDescendingValuesWhilePreservingKeys()
    {
        $list = [1 => 'apple', 2 => 'cat'];
        $this->assertSame(
            [2 => 'cat', 1 => 'apple'],
            Sort::values($list, Order::descending(), Keys::preserve())
        );
    }

    public function testSortingCaseSensitiveStringsWhenFlagIsDeliberatelySet()
    {
        $list = ['aria', 'Apple', 'Bear'];

        $this->assertSame(
            ['Apple', 'Bear', 'aria'],
            Sort::values($list, Keys::discard(), Casing::sensitive())
        );
    }

    public function testSortingCaseInsensitiveString()
    {
        $list = ['aria', 'Apple', 'Bear'];

        $this->assertSame(
            ['Apple', 'aria', 'Bear'],
            Sort::values($list, Keys::discard(), Casing::insensitive())
        );
    }

    public function testPreservingKeysWhenSortingByValue()
    {
        $list = ['a' => 'raspberry', 'b' => 'blueberry', 'c' => 'lemon'];

        $this->assertSame(
            ['b' => 'blueberry', 'c' => 'lemon', 'a' => 'raspberry'],
            Sort::values($list, Keys::preserve())
        );
    }

    public function testSortingByKeys()
    {
        $list = ['c' => 'blat', 'a' => 'foo', 'b' => 'bar'];

        $this->assertSame(['a' => 'foo', 'b' => 'bar', 'c' => 'blat'], Sort::keys($list));
        $this->assertSame(['c' => 'blat', 'a' => 'foo', 'b' => 'bar'], $list, 'Original list should not have been modified');
    }

    public function testSortingKeysWithCaseSensitiveFlag()
    {
        $list = ['aria' => 1, 'Apple' => 1, 'Bear' => 1];

        $this->assertSame(
            ['Apple' => 1, 'Bear' => 1, 'aria' => 1],
            Sort::keys($list, Casing::sensitive())
        );
    }

    public function testSortingKeysCaseInsensitively()
    {
        $list = ['aria' => 1, 'Apple' => 1, 'Bear' => 1];

        $this->assertSame(
            ['Apple' => 1, 'aria' => 1, 'Bear' => 1],
            Sort::keys($list, Casing::insensitive())
        );
    }

    public function testSortingByKeysDescending()
    {
        $list = ['Cat' => 1, 'Apple' => 2, 'Bear' => 3];

        $this->assertSame(
            ['Cat' => 1, 'Bear' => 3, 'Apple' => 2],
            Sort::keys($list, Order::descending())
        );
    }

    public function testNaturalSorting()
    {
        $this->assertSame(
            ["img1.png", "img2.png", "img10.png", "img12.png"],
            Sort::natural(["img12.png", "img10.png", "img2.png", "img1.png"])
        );
    }

    public function testPreservingKeysWithNaturalSorting()
    {
        $this->assertSame(
            [3 => "img1.png", 2 => "img2.png", 1 => "img10.png", 0 => "img12.png"],
            Sort::natural(["img12.png", "img10.png", "img2.png", "img1.png"], Keys::preserve())
        );
    }

    public function testNaturalSortWithCaseSensitiveFlag()
    {
        $this->assertSame(
            ["Img2.png", "Img12.png", "img1.png", "img10.png"],
            Sort::natural(["Img12.png", "img10.png", "Img2.png", "img1.png"], Keys::discard(), Casing::sensitive())
        );
    }

    public function testNaturalSortWithCaseInsensitiveFlag()
    {
        $this->assertSame(
            ["img1.png", "Img2.png", "img10.png", "Img12.png"],
            Sort::natural(["Img12.png", "img10.png", "Img2.png", "img1.png"], Keys::discard(), Casing::insensitive())
        );
    }

    public function testNaturalSortingDescending()
    {
        $this->assertSame(
            ["img12.png", "img10.png", "img2.png", "img1.png"],
            Sort::natural(["img12.png", "img1.png", "img2.png", "img10.png"], Order::descending())
        );
    }

    public function testNaturalSortingDescendingAndPreservingKeys()
    {
        $this->assertSame(
            [0 => "img12.png", 3 => "img10.png", 2 => "img2.png", 1 => "img1.png"],
            Sort::natural(["img12.png", "img1.png", "img2.png", "img10.png"], Order::descending(), Keys::preserve())
        );
    }
    
    public function testSortingCollection()
    {
        $x = new ArrayIterator([3, 2, 5, 1, 4]);

        $this->assertSame([1, 2, 3, 4, 5], Sort::values($x));
    }

    public function testSortingGenerator()
    {
        $generator = function () {
            yield 2;
            yield 1;
            yield 3;
        };

        $this->assertSame([1, 2, 3], Sort::values($generator()));
    }

    public function testSortingByCallback()
    {
        $x = ['derp', 'so', 'foods', 'dat'];

        $comparison = function ($a, $b) {
            return Compare::loose(strlen($a), strlen($b));
        };

        $this->assertSame(
            ['so', 'dat', 'derp', 'foods'],
            Sort::user($x, $comparison)
        );
    }

    public function testDescendingOrderWhenSortingByCallback()
    {
        $x = ['derp', 'so', 'foods', 'dat'];

        $comparison = function ($a, $b) {
            return Compare::loose(strlen($a), strlen($b));
        };

        $this->assertSame(
            ['foods', 'derp', 'dat', 'so'],
            Sort::user($x, $comparison, Order::descending())
        );
    }

    public function testDescendingAndPreservingKeysWhenSortingByCallback()
    {
        $x = ['derp', 'so', 'foods', 'dat'];

        $comparison = function ($a, $b) {
            return Compare::loose(strlen($a), strlen($b));
        };

        $this->assertSame(
            [2 => 'foods', 0 => 'derp', 3 => 'dat', 1 => 'so'],
            Sort::user($x, $comparison, Order::descending(), Keys::preserve())
        );
    }

    public function testPreservingKeysWhenSortingByCallback()
    {
        $x = ['derp', 'so', 'foods', 'dat'];

        $comparison = function ($a, $b) {
            return Compare::loose(strlen($a), strlen($b));
        };

        $this->assertSame(
            [1 => 'so', 3 => 'dat', 0 => 'derp', 2 => 'foods'],
            Sort::user($x, $comparison, Keys::preserve())
        );
    }

    public function testSortingByKeysUsingCallback()
    {
        $x = ['derp' => 1, 'so' => 2, 'foods' => 3, 'dat' => 4];

        $comparison = function ($a, $b) {
            return Compare::loose(strlen($a), strlen($b));
        };

        $this->assertSame(
            ['so' => 2, 'dat' => 4, 'derp' => 1, 'foods' => 3],
            Sort::userKeys($x, $comparison)
        );
    }

    public function testSortKeysDescendingUsingCallback()
    {
        $x = ['derp' => 1, 'so' => 2, 'foods' => 3, 'dat' => 4];

        $comparison = function ($a, $b) {
            return Compare::loose(strlen($a), strlen($b));
        };

        $this->assertSame(
            ['foods' => 3, 'derp' => 1, 'dat' => 4, 'so' => 2],
            Sort::userKeys($x, $comparison, Order::descending())
        );
    }

    public function testSortByShorthand()
    {
        $list = [
            $aisha = new HighScore('Aisha', 3000, new DateTime('June 21, 2015')),
            $ross = new HighScore('Ross', 1000, new DateTime('June 22, 2015')),
            $steven = new HighScore('Steven', 2000, new DateTime('June 21, 2015')),
        ];

        $this->assertSame(
            [$ross, $steven, $aisha],
            Sort::by(
                $list,
                function (HighScore $score) {
                    return $score->getPoints();
                }
            )
        );
    }

    public function testSortByDescending()
    {
        $list = [
            $aisha = new HighScore('Aisha', 3000, new DateTime('June 21, 2015')),
            $ross = new HighScore('Ross', 1000, new DateTime('June 22, 2015')),
            $steven = new HighScore('Steven', 2000, new DateTime('June 21, 2015')),
        ];

        $this->assertSame(
            [$aisha, $steven, $ross],
            Sort::byDescending(
                $list,
                function (HighScore $score) {
                    return $score->getPoints();
                }
            )
        );
    }
}
