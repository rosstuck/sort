<?php
declare(strict_types = 1);

namespace Tuck\Sort\Options\Exception;

use Exception;
use Tuck\Sort\Options\Option;

class OptionSetMultipleTimes extends Exception
{
    public static function ofType($optionType)
    {
        return new static("Option $optionType was set more than once. Each option can only be provided once.");
    }
}