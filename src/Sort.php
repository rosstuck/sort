<?php

namespace Tuck\Sort;

class Sort
{
    const PRESERVE_KEYS = true;
    const DISCARD_KEYS = false;

    const CASE_SENSITIVE = true;
    const CASE_INSENSITIVE = false;

    public static function values($list, $preserveKeys = false, $caseSensitive = true)
    {
        $list = static::normalizeCollection($list);
        $flags = static::normalizeFlags($caseSensitive);

        if ($preserveKeys) {
            asort($list, $flags);
        } else {
            sort($list, $flags);
        }

        return $list;
    }

    public static function keys($list, $caseSensitive = true)
    {
        $list = static::normalizeCollection($list);
        $flags = static::normalizeFlags($caseSensitive);

        ksort($list, $flags);

        return $list;
    }

    public static function natural($list, $preserveKeys = false, $caseSensitive = true)
    {
        $list = static::normalizeCollection($list);

        if ($caseSensitive) {
            natsort($list);
        } else {
            natcasesort($list);
        }

        if (!$preserveKeys) {
            $list = array_values($list);
        }
        return $list;
    }

    public static function user($list, callable $comparison, $preserveKeys = false)
    {
        $list = static::normalizeCollection($list);

        if ($preserveKeys) {
            uasort($list, $comparison);
        } else {
            usort($list, $comparison);
        }

        return $list;
    }

    public static function userKeys($list, callable $comparison)
    {
        $list = static::normalizeCollection($list);
        uksort($list, $comparison);

        return $list;
    }

    public static function by($list, callable $comparison)
    {
        return static::chain()->asc($comparison)->values($list);
    }

    public static function byDescending($list, callable $comparison)
    {
        return static::chain()->desc($comparison)->values($list);
    }

    private static function normalizeCollection($list)
    {
        if (is_array($list)) {
            return $list;
        }

        if (is_object($list) && $list instanceof \Traversable) {
            return iterator_to_array($list);
        }

        throw new \InvalidArgumentException("Expected array or traversable object, received " . gettype($list));
    }

    private function normalizeFlags($caseSensitive)
    {
        $flag = SORT_REGULAR;
        if ($caseSensitive === static::CASE_INSENSITIVE) {
            $flag = SORT_STRING | SORT_FLAG_CASE;
        }

        return $flag;
    }

    public static function chain()
    {
        return new SortChain([]);
    }
}
