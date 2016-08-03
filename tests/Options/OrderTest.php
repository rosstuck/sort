<?php
declare(strict_types = 1);

namespace Tuck\Sort\Tests\Options;

use Tuck\Sort\Options\Order;

class OrderTest extends \PHPUnit_Framework_TestCase
{
    public function testOrderCanBeAscending()
    {
        $order = Order::ascending();

        $this->assertTrue($order->isAscending(), 'Should be ascending');
        $this->assertFalse($order->isDescending(), 'Should not be descending');
        $this->assertEquals(5, $order->buildFlags(5));
    }

    public function testOrderCanBeDescending()
    {
        $order = Order::descending();

        $this->assertTrue($order->isDescending(), 'Should be descending');
        $this->assertFalse($order->isAscending(), 'Should not be ascending');
        $this->assertEquals(5, $order->buildFlags(5));
    }

    public function testDefaultOptionIsAscending()
    {
        $order = Order::defaultSetting();
        $this->assertTrue($order->isAscending());
    }

}
