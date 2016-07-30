<?php

namespace Tuck\Sort;

use Tuck\Sort\Options\Casing;
use Tuck\Sort\Options\Keys;
use Tuck\Sort\Options\Options;
use Tuck\Sort\Options\Order;

class Sort
{
    public static function values($list, ...$options)
    {
        $list = static::normalizeCollection($list);
        $options = new Options($options, [Keys::class, Casing::class, Order::class]);
        $flags = $options->asFlags();

        if ($options->keys()->arePreserved()) {
            if ($options->order()->isAscending()) {
                asort($list, $flags);
            } else {
                arsort($list, $flags);
            }
        } else {
            if ($options->order()->isAscending()) {
                sort($list, $flags);
            } else {
                rsort($list, $flags);
            }
        }

        return $list;
    }

    public static function keys($list, ...$options)
    {
        $list = static::normalizeCollection($list);
        $options = new Options($options, [Casing::class, Order::class]);

        if ($options->order()->isAscending()) {
            ksort($list, $options->asFlags());
        } else {
            krsort($list, $options->asFlags());
        }

        return $list;
    }

    public static function natural($list, ...$options)
    {
        $list = static::normalizeCollection($list);
        $options = new Options($options, [Casing::class, Keys::class, Order::class]);

        if ($options->casing()->isSensitive()) {
            natsort($list);
        } else {
            natcasesort($list);
        }

        if (!$options->order()->isAscending()) {
            $list = array_reverse($list, true);
        }

        if (!$options->keys()->arePreserved()) {
            $list = array_values($list);
        }
        return $list;
    }

    public static function user($list, callable $comparison, ...$options)
    {
        $list = static::normalizeCollection($list);
        $options = new Options($options, [Keys::class, Order::class]);

        if ($options->keys()->arePreserved()) {
            uasort($list, $comparison);
        } else {
            usort($list, $comparison);
        }

        if ($options->order()->isDescending()) {
            $list = array_reverse($list, $options->keys()->arePreserved());
        }

        return $list;
    }

    public static function userKeys($list, callable $comparison, ...$options)
    {
        $list = static::normalizeCollection($list);
        $options = new Options($options, [Order::class]);

        uksort($list, $comparison);

        if ($options->order()->isDescending()) {
            $list = array_reverse($list, true);
        }

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

    public static function chain()
    {
        return new SortChain([]);
    }
}
