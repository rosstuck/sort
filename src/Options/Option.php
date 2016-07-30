<?php
declare(strict_types = 1);

namespace Tuck\Sort\Options;

interface Option
{
    public function buildFlags($flags);

    public static function defaultOption();
}