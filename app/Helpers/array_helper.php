<?php
/**
 * Porting of PHP 8.4 function
 *
 * @template TValue of mixed
 * @template TKey of array-key
 *
 * @param array<TKey, TValue> $array
 * @param callable(TValue $value, TKey $key): bool $callback
 * @return ?TValue
 *
 * @see https://www.php.net/manual/en/function.array-find.php
 */
if (! function_exists('array_find')) {
    function array_find($array, $callback)
    {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }
        return null;
    }
}
