<?php

namespace Tuck\Sort;

class Sort
{
    const PRESERVE_KEYS = true;
    const DISCARD_KEYS = false;

    public static function values($list, $preserveKeys = false)
    {
        $list = static::normalizeCollection($list);

        if ($preserveKeys) {
            asort($list);
        } else {
            sort($list);
        }

        return $list;
    }

    public static function keys($list)
    {
        $list = static::normalizeCollection($list);
        ksort($list);

        return $list;
    }

    public static function natural($list, $preserveKeys = false)
    {
        $list = static::normalizeCollection($list);
        natsort($list);

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

    public static function reversedBy($list, callable $comparison)
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

    public static function chain()
    {
        return new SortChain([]);
    }
}
