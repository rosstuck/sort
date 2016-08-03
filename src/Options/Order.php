<?php
declare(strict_types = 1);

namespace Tuck\Sort\Options;

class Order implements Option
{
    private $ascending;

    private function __construct($ascending)
    {
        $this->ascending = $ascending;
    }

    public static function ascending()
    {
        return new static(true);
    }

    public static function descending()
    {
        return new static(false);
    }

    public function isAscending()
    {
        return $this->ascending;
    }

    public function isDescending()
    {
        return !$this->ascending;
    }

    public function buildFlags($flags)
    {
        return $flags;
    }

    public static function defaultSetting()
    {
        return static::ascending();
    }
}