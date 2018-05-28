# Tuck\Sort

[![Build Status](https://travis-ci.org/rosstuck/sort.svg?branch=master)](https://travis-ci.org/rosstuck/sort)
[![Latest Stable Version](https://poser.pugx.org/tuck/sort/v/stable.png)](https://packagist.org/packages/WyriHaximus/react-http-middleware-session)
[![Total Downloads](https://poser.pugx.org/tuck/sort/downloads.png)](https://packagist.org/packages/tuck/sort)
[![License](https://poser.pugx.org/tuck/sort/license.png)](https://packagist.org/packages/WyriHaximus/react-http-middleware-session)

Syntactic sugar for PHP's built in sorting.

## Examples

Basic sorting functions

```php
use Tuck\Sort\Sort;

Sort::values(['foo', 'bar', 'baz']);                    // returns ['bar', 'baz', 'foo']
Sort::keys(['x' => 'foo', 'm' => 'bar']);               // returns ['m' => 'bar', 'x' => 'foo']
Sort::natural(['img12.jpg', 'img2.jpg', 'img1.jpg']);   // returns ['img1.jpg', 'img2.jpg', 'img12.jpg']
Sort::user(
    [3, 2, 5, 6],
    function () { /* custom sorting */ }
);
```

## Why?
This library tries to smooth out the built-in API, specifically the following issues:

### PHP requires variables.

If you want to sort the result of a method, you need to assign it to an intermediate variable.

```php
$results = $metrics->getTotals();
sort($results);
```

With this library, you can pass results directly:

```php
Sort::values($metrics->getTotals());
```

### PHP modifies in-place
Perhaps you'd prefer sort to return a modified list, rather than mutating the original. Unfortunately, the return value of ```sort()``` is a success boolean, not the reordered list.

With this library, the results are returned directly and the original list is not modified:

```php
$x = [3, 1, 2];

var_dump(Sort::values($x)); // 1, 2, 3
var_dump($x);               // 3, 1, 2
```

### PHP's naming is confusing
PHP's sorting functions don't always have the most intuitive names:

```php
asort()
sort()
ksort()
natsort()
usort()
uasort()
uksort()
```

With this library, naming reads a little better:

```php
Sort::values()
Sort::keys()
Sort::natural()
Sort::user()
```

### PHP doesn't handle keys consistently
When you use ```sort()``` PHP discards the original keys. Unless you use ```asort()```. Or ```natsort``` and ```natcasesort``` which always preserve the original keys. You get the idea.

With this library, the returned keys are reset to sequential numbers, regardless of the type of sorting.

```php
Sort::values([3 => 'bob', 1 => 'alice']);
// returns [0 => 'alice', 1 => 'bob']
```

If you'd like to keep the original keys, there's a consistent parameter you can always use ```Sort::PRESERVE_KEYS```. This selects the correct key-preserving function under the hood, so you don't have to remember it.

```php
Sort::values([3 => 'bob', 1 => 'alice'], Sort::PRESERVE_KEYS);
// returns [1 => 'alice', 3 => 'bob']
```

This works for all sort functions:
```php
Sort::values(['foo', 'bar', 'baz'], Sort::PRESERVE_KEYS);
Sort::natural(['foo', 'bar', 'baz'], Sort::PRESERVE_KEYS);
Sort::user(
    [3, 2, 5, 6],
    function () { /* custom sorting */ },
    Sort::PRESERVE_KEYS
);
```

Much easier to remember. If the constants are too long-winded or readable, you can also pass ```true``` to preserve the keys or ```false``` to discard them.

### PHP doesn't accept iterators or generators

Built-in sorting functions work great on arrays but won't accept their Traversable brethren.

With this library, iterators and generators are automatically converted to arrays.

```php
$x = new ArrayIterator([3, 1, 2]);
Sort::values($x); // returns [1, 2, 3]
```

Note this library always returns arrays, even when given a custom collection. If you'd like your custom collection to support this library, see the usort documentation below.

### PHP doesn't have a shorthand for sorting by fields
When you're comparing a list of objects, you usually want to compare the same field on them repeatedly. Usually that means writing a usort function like this:

```php
Sort::user($list, function (HighScore $a, HighScore $b) {
    return $a->getPoints() <=> $b->getPoints();
});
```

And that's with the PHP 7 shorthand operator helping. This library offers a slightly shorter, Scala inspired version where you only specify how to retrieve the sortable data from an element.

```php
Sort::by($list, function (HighScore $a) {
    return $a->getPoints();
});
```

This also works in reverse:

```php
Sort::byDescending($list, function (HighScore $a) {
    return $a->getPoints();
});
```

### PHP doesn't have chained usort

There's no elegant syntax for chaining multiple sorts at once. If you wanted to sort a list of high scores, first by points, then name, then date, you'd need to write a function like:

```php
usort(
    $unsorted,
    function (HighScore $scoreA, HighScore $scoreB) {
        $a = $scoreA->getPoints();
        $b = $scoreA->getPoints();

        if ($a == $b) {
            $a = $scoreA->getDate();
            $b = $scoreB->getDate();
        }

        if ($a == $b) {
            $a = $scoreA->getName();
            $b = $scoreB->getName();
        }

        return $a <=> $b;
    }
);
```

With this library, you can chain sorts like so:

```php
Sort::chain()
    ->compare(function (HighScore $a, HighScore $b) {
        return $a->getPoints() <=> $b->getPoints();
    })
    ->compare(function (HighScore $a, HighScore $b) {
        return $a->getDate() <=> $b->getDate();
    })
    ->compare(function (HighScore $a, HighScore $b) {
        return $a->getName() <=> $b->getName();
    });
```

In most cases, you'll want to extract the same information from ```$a``` and ```$b``` at the same time. You might also want to sort them as ascending or descending on different factors. For both of these use cases, you can use the ```asc()``` and ```desc()``` methods.

```php
$sortChain = Sort::chain()
    ->desc(function (HighScore $score) {
        return $score->getPoints();
    })
    ->asc(function (HighScore $score) {
        return $score->getDate();
    })
    ->asc(function (HighScore $score) {
        return $score->getName();
    });

```

Once you've created your sorting chain, you can apply it to keys or values. Features like Iterator support, returned values, and PRESERVE_KEY flag are all supported.

```php
$sortChain->values(['foo', 'bar']);
$sortChain->values(['foo', 'bar'], Sort::PRESERVE_KEYS);
$sortChain->keys(['foo' => 'blah', 'bar' => 'blat']);
```

You can also invoke the chain itself as a comparison function:

```php
$sortChain('steven', 'connie'); // returns -1, 0 or 1
```

This means you can use it with any custom collection class that supports usort:
```php
$yourCustomCollection->usort($sortChain);
```

When building your sort chain, you don't have to compose all the sorting functions at once. You can attach them over time or conditionally:

```php
$sortOrder = Sort::chain();

if ($options['sort_by_name']) {
   $sortOrder->asc(...);
}

// etc
```

However, if use the chain before any sorts are added, then the order is essentially random and decided by your PHP runtime (PHP 5.x vs PHP 7 vs HHVM). There's no easy way to normalize this (I've tried) but it varies based on the runtime, the number of elements in the array, the sorting algorithm used, etc. The good news is it doesn't matter if the elements are random because there was nothing worth sorting by anyways! :)

## License

[MIT](https://opensource.org/licenses/MIT)

## Contributing

PRs welcome. :) Please abide by PSR-2, use tests, and respect the [Code Manifesto](http://codemanifesto.com/).

## Status
Working, tested, still need to add non-functionals like docs and fancy README badges.
