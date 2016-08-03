<?php
declare(strict_types = 1);

namespace Tuck\Sort\Tests\Options;

use DateTime;
use Tuck\Sort\Options\Casing;
use Tuck\Sort\Options\Keys;
use Tuck\Sort\Options\Options;

class OptionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Tuck\Sort\Options\Exception\NotAnOption
     */
    public function testOnlyAcceptsOptions()
    {
        new Options([Keys::discard(), new DateTime()], [Keys::class, DateTime::class]);
    }

    /**
     * @expectedException \Tuck\Sort\Options\Exception\OptionSetMultipleTimes
     */
    public function testOnlyAcceptsOneOfEachType()
    {
        new Options([Keys::discard(), Keys::discard()], [Keys::class]);
    }

    /**
     * @expectedException \Tuck\Sort\Options\Exception\UnsupportedOption
     */
    public function testWillOnlyAcceptSupportedOptions()
    {
        new Options([Keys::discard()], [Casing::class]);
    }

    public function testFallsBackToDefaultOption()
    {
        $options = new Options([Keys::preserve()], [Keys::class]);

        $this->assertTrue($options->keys()->arePreserved(), 'Keys option not accepted');
        $this->assertTrue($options->order()->isAscending(), 'Order default missing');
        $this->assertTrue($options->casing()->isSensitive(), 'Casing default missing');
    }

    public function testCanChainTogetherFlags()
    {
        $options = new Options(
            [new MockOptionOne(SORT_STRING), new MockOptionTwo(SORT_FLAG_CASE)],
            [MockOptionOne::class, MockOptionTwo::class]
        );
        $this->assertEquals(SORT_STRING | SORT_FLAG_CASE, $options->asFlags());
    }

    public function testDefaultsToStandardFlagValue()
    {
        $options = new Options([], []);
        $this->assertEquals(SORT_REGULAR, $options->asFlags());
    }
}
