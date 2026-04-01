<?php

declare(strict_types=1);

namespace CaseproofUtils\Tests;

use PHPUnit\Framework\TestCase;
use CaseproofUtils\ArrayUtils;

class ArrayUtilsTest extends TestCase
{
    // ── flatten ──

    public function testFlattenSimple(): void
    {
        $this->assertSame([1, 2, 3], ArrayUtils::flatten([1, [2], 3]));
    }

    public function testFlattenDeeplyNested(): void
    {
        $this->assertSame([1, 2, 3, 4, 5], ArrayUtils::flatten([1, [2, [3, 4]], 5]));
    }

    public function testFlattenAlreadyFlat(): void
    {
        $this->assertSame([1, 2, 3], ArrayUtils::flatten([1, 2, 3]));
    }

    public function testFlattenTripleNested(): void
    {
        $this->assertSame([1, 2, 3], ArrayUtils::flatten([[[1]], [[2]], [[3]]]));
    }

    // ── groupBy ──

    public function testGroupByBasic(): void
    {
        $items = [
            ['type' => 'fruit', 'name' => 'apple'],
            ['type' => 'veggie', 'name' => 'carrot'],
            ['type' => 'fruit', 'name' => 'banana'],
        ];

        $result = ArrayUtils::groupBy($items, 'type');

        $this->assertCount(2, $result);
        $this->assertCount(2, $result['fruit']);
        $this->assertCount(1, $result['veggie']);
    }

    public function testGroupByPreservesAllItems(): void
    {
        $items = [
            ['status' => 'active', 'id' => 1],
            ['status' => 'active', 'id' => 2],
            ['status' => 'active', 'id' => 3],
        ];

        $result = ArrayUtils::groupBy($items, 'status');

        $this->assertCount(3, $result['active']);
    }

    // ── unique ──

    public function testUniqueBasic(): void
    {
        $this->assertSame([1, 2, 3], ArrayUtils::unique([1, 2, 2, 3, 3, 3]));
    }

    public function testUniqueReindexes(): void
    {
        $result = ArrayUtils::unique([10, 20, 20, 30]);

        // Keys should be 0, 1, 2 — not 0, 1, 3
        $this->assertSame([0, 1, 2], array_keys($result));
        $this->assertSame([10, 20, 30], $result);
    }

    public function testUniqueStrings(): void
    {
        $this->assertSame(['a', 'b', 'c'], ArrayUtils::unique(['a', 'b', 'a', 'c', 'b']));
    }

    // ── pluck ──

    public function testPluckBasic(): void
    {
        $items = [
            ['name' => 'Alice', 'age' => 30],
            ['name' => 'Bob', 'age' => 25],
        ];

        $this->assertSame(['Alice', 'Bob'], ArrayUtils::pluck($items, 'name'));
    }

    public function testPluckSkipsMissingKeys(): void
    {
        $items = [
            ['name' => 'Alice'],
            ['age' => 25],
            ['name' => 'Charlie'],
        ];

        // Should skip the item that doesn't have 'name', not throw an error
        $this->assertSame(['Alice', 'Charlie'], ArrayUtils::pluck($items, 'name'));
    }
}
