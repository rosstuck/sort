<?php

namespace Tuck\Sort;

class SortChain
{
    private $callables = [];

    public function __construct(array $callables)
    {
        $this->callables = $callables;
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

    public function values($collection, ...$options)
    {
        return call_user_func_array([Sort::class, 'user'], array_merge([$collection, $this], $options));
    }

    public function keys($collection)
    {
        return Sort::userKeys($collection, $this);
    }

    public function __invoke($a, $b)
    {
        if (empty($this->callables)) {
            return 0;
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

    private function reverse(callable $callable)
    {
        return function ($a, $b) use ($callable) {
            return $callable($a, $b) * (-1);
        };
    }
}
