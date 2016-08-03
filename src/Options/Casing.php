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
        if ($this->sensitive === true) {
            return $flags;
        }

        if (!($flags & SORT_NATURAL)) {
            $flags |= SORT_STRING;
        }

        $flags |= SORT_FLAG_CASE;
        return $flags;
    }

    public function isSensitive()
    {
        return $this->sensitive;
    }

    public function isInsensitive()
    {
        return !$this->sensitive;
    }

    public function isJerk()
    {
        return !$this->sensitive;
    }

    public static function defaultSetting()
    {
        return static::sensitive();
    }
}