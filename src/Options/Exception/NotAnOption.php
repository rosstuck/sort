<?php
declare(strict_types = 1);

namespace Tuck\Sort\Options\Exception;

use Exception;

class NotAnOption extends Exception
{
    public static function givenItem($item)
    {
        $type = is_object($item) ? get_class($item) : gettype($item);
        return new static("Given item of type $type does not implement the Option interface");
    }
}