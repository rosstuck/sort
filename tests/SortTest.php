<?php

namespace Tuck\Sort\Tests;

use ArrayIterator;
use DateTime;
use Tuck\Sort\Sort;
use PHPUnit\Framework\TestCase;

class SortTest extends TestCase
{
    public function testSortingByValues()
    {
        $list = [3, 2, 4, 1];

        $this->assertSame([1, 2, 3, 4], Sort::values($list));
        $this->assertSame([3, 2, 4, 1], $list, 'Original list should not have been modified');
    }

    public function testSortingCaseSensitiveStringsWhenFlagIsDeliberatelySet()
    {
        $list = ['aria', 'Apple', 'Bear'];

        $this->assertSame(
            ['Apple', 'Bear', 'aria'],
            Sort::values($list, Sort::DISCARD_KEYS, Sort::CASE_SENSITIVE)
        );
    }

    public function testSortingCaseInsensitiveString()
    {
        $list = ['aria', 'Apple', 'Bear'];

        $this->assertSame(
            ['Apple', 'aria', 'Bear'],
            Sort::values($list, Sort::DISCARD_KEYS, Sort::CASE_INSENSITIVE)
        );
    }

    public function testPreservingKeysWhenSortingByValue()
    {
        $list = ['a' => 'raspberry', 'b' => 'blueberry', 'c' => 'lemon'];

        $this->assertSame(
            ['b' => 'blueberry', 'c' => 'lemon', 'a' => 'raspberry'],
            Sort::values($list, Sort::PRESERVE_KEYS)
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
            Sort::keys($list, Sort::CASE_SENSITIVE)
        );
    }

    public function testSortingKeysCaseInsensitively()
    {
        $list = ['aria' => 1, 'Apple' => 1, 'Bear' => 1];

        $this->assertSame(
            ['Apple' => 1, 'aria' => 1, 'Bear' => 1],
            Sort::keys($list, Sort::CASE_INSENSITIVE)
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
            Sort::natural(["img12.png", "img10.png", "img2.png", "img1.png"], Sort::PRESERVE_KEYS)
        );
    }

    public function testNaturalSortWithCaseSensitiveFlag()
    {
        $this->assertSame(
            ["Img2.png", "Img12.png", "img1.png", "img10.png"],
            Sort::natural(["Img12.png", "img10.png", "Img2.png", "img1.png"], Sort::DISCARD_KEYS, Sort::CASE_SENSITIVE)
        );
    }

    public function testNaturalSortWithCaseInsensitiveFlag()
    {
        $this->assertSame(
            ["img1.png", "Img2.png", "img10.png", "Img12.png"],
            Sort::natural(["Img12.png", "img10.png", "Img2.png", "img1.png"], Sort::DISCARD_KEYS, Sort::CASE_INSENSITIVE)
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
            return strlen($a) <=> strlen($b);
        };

        $this->assertSame(
            ['so', 'dat', 'derp', 'foods'],
            Sort::user($x, $comparison),
            true
        );
    }

    public function testPreservingKeysWhenSortingByCallback()
    {
        $x = ['derp', 'so', 'foods', 'dat'];

        $comparison = function ($a, $b) {
            return strlen($a) <=> strlen($b);
        };

        $this->assertSame(
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
