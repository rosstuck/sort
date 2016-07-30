<?php

namespace Tuck\Sort\Tests;

use DateTime;
use ArrayObject;
use Tuck\Sort\Compare;
use Tuck\Sort\Options\Keys;
use Tuck\Sort\Sort;

class SortChainTest extends \PHPUnit_Framework_TestCase
{
    public function testSortingChain()
    {
        $unsorted = [
            $aisha = new HighScore('Aisha', 3000, new DateTime('June 21, 2015')),
            $ross = new HighScore('Ross', 1000, new DateTime('June 22, 2015')),
            $steven = new HighScore('Steven', 2000, new DateTime('June 21, 2015')),
        ];

        $func = Sort::chain()
            ->desc(function (HighScore $score) {
                return $score->getPoints();
            })
            ->asc(function (HighScore $score) {
                return $score->getDate();
            })
            ->asc(function (HighScore $score) {
                return $score->getName();
            });

        $this->assertEquals([$aisha, $steven, $ross], $func->values($unsorted));
    }

    public function testPreservingKeysWhenSorting()
    {
        $unsorted = ['citrus' => 'orange', 'berry' => 'blueberry'];

        $chain = Sort::chain()->compare(function($a, $b) {
            return Compare::loose($a, $b);
        });

        $this->assertEquals(['blueberry', 'orange'], $chain->values($unsorted));
        $this->assertEquals(['blueberry', 'orange'], $chain->values($unsorted, Keys::discard()));
        $this->assertEquals(['berry' => 'blueberry', 'citrus' => 'orange'], $chain->values($unsorted, Keys::preserve()));
    }

    public function testApplyingToACustomCollection()
    {
        $object = new ArrayObject([
            $aisha = new HighScore('Aisha', 3000, new DateTime('June 21, 2015')),
            $ross = new HighScore('Ross', 1000, new DateTime('June 22, 2015')),
            $steven = new HighScore('Steven', 2000, new DateTime('June 21, 2015')),
        ]);

        $func = Sort::chain()
            ->desc(function (HighScore $score) {
                return $score->getPoints();
            })
            ->asc(function (HighScore $score) {
                return $score->getDate();
            })
            ->asc(function (HighScore $score) {
                return $score->getName();
            });

        $object->uasort($func);

        $this->assertEquals(
            [$aisha, $steven, $ross],
            array_values(iterator_to_array($object))
        );
    }

    public function testUsingFullCustomComparisonFunction()
    {
        $unsorted = [
            $steven = new HighScore('Steven', 2000, new DateTime('June 21, 2015')),
            $aisha = new HighScore('Aisha', 3000, new DateTime('June 21, 2015')),
            $ross = new HighScore('Ross', 1000, new DateTime('June 22, 2015')),
        ];

        $sorted = Sort::chain()
            ->compare(function (HighScore $a, HighScore $b) {
                return Compare::loose($a->getDate(), $b->getDate());
            })
            ->compare(function (HighScore $a, HighScore $b) {
                return Compare::loose($a->getName(), $b->getName());
            })
            ->values($unsorted);

        $this->assertEquals(
            [$aisha, $steven, $ross],
            $sorted
        );
    }

    public function testAnEmptyMethodPassesButPromisesNoOrder()
    {
        $sorted = Sort::chain()->values([
            $aisha = new HighScore('Aisha', 3000, new DateTime('June 21, 2015')),
            $ross = new HighScore('Ross', 1000, new DateTime('June 22, 2015')),
            $steven = new HighScore('Steven', 2000, new DateTime('June 21, 2015')),
        ]);

        $this->assertCount(3, $sorted);
        $this->assertContains($aisha, $sorted);
        $this->assertContains($steven, $sorted);
        $this->assertContains($ross, $sorted);
    }
}
