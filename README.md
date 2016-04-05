# Tuck\Sort

Some syntactic sugar for PHP's built in sorting functions. Adds some consistency and features:

- Sort functions accept iterators (but still return arrays)
- More consistent API for preserving vs discarding keys
- Returns sorted array instead of modifying variable
- Accepts parameters rather than requiring assignment to variable
- Helpers for creating complex sortings on multiple fields
- Works in PHP 7 (spaceship!) but can shorten PHP 5 usort boilerplate

## Examples

Basic sorting functions

```
use Tuck\Sort\Sort;

Sort::values(['foo', 'bar', 'baz']);                    // returns ['foo', 'bar', 'baz']
Sort::keys(['x' => 'foo', 'm' => 'bar']);               // returns ['m' => 'bar', 'x' => 'foo']
Sort::natural(['img12.jpg', 'img2.jpg', 'img1.jpg']);   // returns ['img1.jpg', 'img2.jpg', 'img12.jpg']
Sort::user(
    [3, 2, 5, 6],
    function () { /* custom sorting */ }
);
```

Automatically converts iterators
```
$x = new ArrayIterator([3, 4, 1, 2]);
Sort::values($x); // returns [1, 2, 3, 4]
```

No more `sort` vs `asort` or remembering to index your test fixtures when using `natsort`.

```
Sort::values(['foo', 'bar', 'baz'], Sort::PRESERVE_KEYS);
Sort::natural(['foo', 'bar', 'baz'], Sort::PRESERVE_KEYS);
Sort::user(
    [3, 2, 5, 6],
    function () { /* custom sorting */ },
    Sort::PRESERVE_KEYS
);
```

Simplify the usort ```$a == $b ? 0 : (($a < $b) ? -1 : 1);``` mess in PHP 5

```
use Tuck\Sort\Compare;

Compare::loose(1, "1"); // uses ==
Compare::strict(1, 1)   // uses ===

// but seriously, upgrade to 7, spaceship operator is awesome
```

Chain sorting methods
```
$sortChain = Sort::chain()
    ->desc(function (HighScore $score) {
        return $score->getScore();
    })
    ->asc(function (HighScore $score) {
        return $score->getDate();
    })
    ->asc(function (HighScore $score) {
        return $score->getName();
    });

// then apply it to keys or values. Iterator support, returned values,
// and PRESERVE_KEY flag are all supported.
$sortChain->values(['foo', 'bar']);
$sortChain->values(['foo', 'bar'], Sort::PRESERVE_KEYS);
$sortChain->keys(['foo' => 'blah', 'bar' => 'blat']);

// or use it as a comparison function that returns 1, 0, or -1
$sortChain('steven', 'connie');

// meaning you can use it with any custom collection class that supports usort!
$yourCustomCollection->usort($sortChain);
```

## Status
Works, tested, still need to add non-functionals like docs and fancy README badges.
