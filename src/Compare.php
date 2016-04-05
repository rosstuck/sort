<?php

namespace Tuck\Sort;

class Compare
{
    public static function loose($a, $b)
    {
        if ($a == $b) {
            return 0;
        }

        return ($a < $b) ? -1 : 1;
    }

    public static function strict($a, $b)
    {
        if ($a === $b) {
            return 0;
        }

        return ($a < $b) ? -1 : 1;
    }
}