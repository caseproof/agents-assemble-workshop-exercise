<?php

declare(strict_types=1);

namespace CaseproofUtils;

class ArrayUtils
{
    /**
     * Flatten a nested array to a single level.
     * [1, [2, [3, 4]], 5] => [1, 2, 3, 4, 5]
     *
     * BUG: Only flattens one level deep instead of recursively.
     */
    public static function flatten(array $array): array
    {
        $result = [];

        foreach ($array as $item) {
            if (is_array($item)) {
                foreach ($item as $subItem) {
                    $result[] = $subItem;
                }
            } else {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * Group an array of associative arrays by a given key.
     * [['type' => 'a', 'val' => 1], ['type' => 'b', 'val' => 2], ['type' => 'a', 'val' => 3]]
     * grouped by 'type' => ['a' => [['type' => 'a', 'val' => 1], ['type' => 'a', 'val' => 3]], 'b' => [...]]
     *
     * BUG: Only keeps the last item per group instead of collecting all items.
     */
    public static function groupBy(array $items, string $key): array
    {
        $result = [];

        foreach ($items as $item) {
            if (isset($item[$key])) {
                $result[$item[$key]] = $item;
            }
        }

        return $result;
    }

    /**
     * Return unique values from an array, preserving the first occurrence's key.
     * Re-indexes the result array starting from 0.
     *
     * BUG: Doesn't re-index the result (preserves original keys instead).
     */
    public static function unique(array $array): array
    {
        return array_unique($array);
    }

    /**
     * Pluck a single key's values from an array of associative arrays.
     * [['name' => 'Alice', 'age' => 30], ['name' => 'Bob', 'age' => 25]] plucked by 'name' => ['Alice', 'Bob']
     *
     * BUG: Throws an error instead of skipping items that don't have the key.
     */
    public static function pluck(array $items, string $key): array
    {
        $result = [];

        foreach ($items as $item) {
            $result[] = $item[$key];
        }

        return $result;
    }
}
