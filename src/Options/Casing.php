<?php
declare(strict_types = 1);

namespace Tuck\Sort\Options;

class Casing implements Option
{
    private $sensitive = true;

    private function __construct($sensitive)
    {
        $this->sensitive = $sensitive;
    }

    public static function sensitive()
    {
        return new static(true);
    }

    public static function insensitive()
    {
        return new static(false);
    }

    public function buildFlags($flags)
    {
        $flag = SORT_REGULAR;
        if ($this->sensitive === false) {
            $flag = SORT_STRING | SORT_FLAG_CASE;
        }

        return $flag;
    }

    public function isSensitive()
    {
        return $this->sensitive;
    }

    public static function defaultOption()
    {
        return static::sensitive();
    }
}