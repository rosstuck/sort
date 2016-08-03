<?php
declare(strict_types = 1);

namespace Tuck\Sort\Options;

class Keys implements Option
{
    private $preserve;

    private function __construct($preserve)
    {
        $this->preserve = $preserve;
    }

    public static function preserve()
    {
        return new static(true);
    }

    public static function discard()
    {
        return new static(false);
    }

    public function arePreserved()
    {
        return $this->preserve;
    }
    
    public function areDiscarded()
    {
        return !$this->preserve;
    }

    public function buildFlags($flags)
    {
        return $flags;
    }

    public static function defaultSetting()
    {
        return static::discard();
    }
}