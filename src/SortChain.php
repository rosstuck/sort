<?php

namespace Tuck\Sort;

class SortChain
{
    private $callables = [];

    private static $nullOperatorEquality;

    public function __construct(array $callables)
    {
        $this->callables = $callables;

        if (static::$nullOperatorEquality === null) {
            static::$nullOperatorEquality = version_compare(PHP_VERSION, '7.0.0', '>=') ? 0 : 1;
        }
    }

    public function compare(callable $callable)
    {
        return new static(array_merge($this->callables, [$callable]));
    }

    public function asc(callable $callable)
    {
        return $this->compare($this->singleToComparison($callable));
    }

    public function desc(callable $callable)
    {
        return $this->compare(
            $this->reverse(
                $this->singleToComparison($callable)
            )
        );
    }

    public function values($collection, $preserveKeys = Sort::DISCARD_KEYS)
    {
        return Sort::user($collection, $this, $preserveKeys);
    }

    public function keys($collection)
    {
        return Sort::userKeys($collection, $this);
    }

    public function __invoke($a, $b)
    {
        if (empty($this->callables)) {
            return static::$nullOperatorEquality;
        }

        foreach ($this->callables as $callable) {
            $result = $callable($a, $b);

            if ($result !== 0) {
                return $result;
            }
        }

        return 0;
    }

    private function singleToComparison(callable $callable)
    {
        return function ($a, $b) use ($callable) {
            return Compare::loose($callable($a), $callable($b));
        };
    }

    public function reverse(callable $callable)
    {
        return function ($a, $b) use ($callable) {
            return $callable($a, $b) * (-1);
        };
    }
}
