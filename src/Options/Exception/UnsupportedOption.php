<?php
declare(strict_types = 1);

namespace Tuck\Sort\Options\Exception;

use Exception;
use Tuck\Sort\Options\Option;

class UnsupportedOption extends Exception
{
    /**
     * @param string[] $unsupportedTypes
     * @return static
     */
    public static function multipleTypes($unsupportedTypes)
    {
        return new static("The following options are not supported with this type of sort: " . implode(',', $unsupportedTypes));
    }
}