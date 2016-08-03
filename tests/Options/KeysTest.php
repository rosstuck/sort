<?php
declare(strict_types = 1);

namespace Tuck\Sort\Tests\Options;

use Tuck\Sort\Options\Keys;

class KeysTest extends \PHPUnit_Framework_TestCase
{
    public function testKeysCanBePreserved()
    {
        $keys = Keys::preserve();

        $this->assertTrue($keys->arePreserved(), 'Keys should be preserved');
        $this->assertFalse($keys->areDiscarded(), 'Keys should not be discarded');
        $this->assertEquals(5, $keys->buildFlags(5));
    }

    public function testKeysCanBeDiscarded()
    {
        $keys = Keys::discard();

        $this->assertTrue($keys->areDiscarded(), 'Keys should be discarded');
        $this->assertFalse($keys->arePreserved(), 'Keys should not be preserved');
        $this->assertEquals(5, $keys->buildFlags(5));
    }

    public function testDefaultIsDiscarded()
    {
        $this->assertTrue(Keys::defaultSetting()->areDiscarded());
    }
}
