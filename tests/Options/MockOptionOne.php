<?php
declare(strict_types = 1);

namespace Tuck\Sort\Tests\Options;

use Tuck\Sort\Options\Option;

class MockOptionOne implements Option
{
    private $flagValue;

    public function __construct($flagValue)
    {
        $this->flagValue = $flagValue;
    }

    public function buildFlags($flags)
    {
        return $flags | $this->flagValue;
    }

    public static function defaultSetting()
    {
        return 'foo';
    }
}