<?php
declare(strict_types = 1);

namespace Tuck\Sort\Tests\Options;

use Tuck\Sort\Options\Casing;

class CasingTest extends \PHPUnit_Framework_TestCase
{
    public function testCasingCanBeSensitive()
    {
        $casing = Casing::sensitive();

        $this->assertTrue($casing->isSensitive(), 'Should be sensitive');
        $this->assertFalse($casing->isInsensitive(), 'Should not be insensitive');
    }

    public function testCasingCanBeInsensitive()
    {
        $casing = Casing::insensitive();

        $this->assertTrue($casing->isInsensitive(), 'Should be insensitive');
        $this->assertFalse($casing->isSensitive(), 'Should not be sensitive');
    }

    public function testDoesNotChangeFlagsWhenCaseSensitive()
    {
        $this->assertEquals(
            SORT_REGULAR,
            Casing::sensitive()->buildFlags(SORT_REGULAR)
        );
    }

    public function testCanAddCaseInsensitiveFlags()
    {
        $this->assertEquals(
            SORT_REGULAR | SORT_STRING | SORT_FLAG_CASE,
            Casing::insensitive()->buildFlags(SORT_REGULAR)
        );
    }

    public function testWillNotSetSortStringIfSortNaturalIsAlreadySet()
    {
        $this->assertEquals(
            SORT_REGULAR | SORT_STRING | SORT_FLAG_CASE,
            Casing::insensitive()->buildFlags(SORT_REGULAR)
        );
    }

    public function testWillNotTamperWithFlagsWhenCaseSensitive()
    {
        $this->assertEquals(
            SORT_REGULAR,
            Casing::sensitive()->buildFlags(SORT_REGULAR)
        );
    }

    public function testWillAddSortStringAndCaseFlag()
    {
        $this->assertEquals(
            SORT_STRING | SORT_FLAG_CASE,
            Casing::insensitive()->buildFlags(SORT_REGULAR)
        );
    }

    public function testWillNotAddSortStringWhenSortNaturalIsAlreadyDefined()
    {
        $this->assertEquals(
            SORT_NATURAL | SORT_FLAG_CASE,
            Casing::insensitive()->buildFlags(SORT_NATURAL)
        );
    }

    public function testWillNotGoHaywireWhenSortStringIsAlreadySet()
    {
        $this->assertEquals(
            SORT_STRING | SORT_FLAG_CASE,
            Casing::insensitive()->buildFlags(SORT_STRING)
        );
    }
}
