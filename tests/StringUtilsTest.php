<?php

declare(strict_types=1);

namespace CaseproofUtils\Tests;

use PHPUnit\Framework\TestCase;
use CaseproofUtils\StringUtils;

class StringUtilsTest extends TestCase
{
    // ── slugify ──

    public function testSlugifyBasic(): void
    {
        $this->assertSame('hello-world', StringUtils::slugify('Hello World'));
    }

    public function testSlugifyLowercases(): void
    {
        $this->assertSame('my-post-title', StringUtils::slugify('My Post Title'));
    }

    public function testSlugifyCollapsesMultipleHyphens(): void
    {
        $this->assertSame('hello-world', StringUtils::slugify('hello - - world'));
    }

    public function testSlugifyHandlesSpecialCharacters(): void
    {
        $this->assertSame('whats-new-in-2026', StringUtils::slugify("What's New in 2026?!"));
    }

    // ── truncate ──

    public function testTruncateShortString(): void
    {
        $this->assertSame('Hello', StringUtils::truncate('Hello', 10));
    }

    public function testTruncateAtWordBoundary(): void
    {
        $text = 'The quick brown fox jumps over the lazy dog';
        $result = StringUtils::truncate($text, 20);

        // Should not break mid-word — the truncation point should be at a word boundary
        $this->assertStringNotContainsString('fox j', $result, 'Should not break in the middle of a word');
        $this->assertLessThanOrEqual(20, mb_strlen($result));
        $this->assertStringEndsWith('...', $result);
    }

    public function testTruncateWithCustomSuffix(): void
    {
        $text = 'The quick brown fox jumps over the lazy dog';
        $result = StringUtils::truncate($text, 20, ' [more]');

        $this->assertLessThanOrEqual(20, mb_strlen($result));
        $this->assertStringEndsWith('[more]', $result);
    }

    public function testTruncateMaxLengthShorterThanSuffix(): void
    {
        // When max length is shorter than the suffix, should return the suffix truncated or handle gracefully
        $result = StringUtils::truncate('Hello world', 2);
        $this->assertLessThanOrEqual(2, mb_strlen($result));
    }

    // ── initials ──

    public function testInitialsBasic(): void
    {
        $this->assertSame('JD', StringUtils::initials('John Doe'));
    }

    public function testInitialsThreeNames(): void
    {
        $this->assertSame('MJW', StringUtils::initials('Mary Jane Watson'));
    }

    public function testInitialsExtraWhitespace(): void
    {
        $this->assertSame('JD', StringUtils::initials('  John   Doe  '));
    }

    public function testInitialsSingleName(): void
    {
        $this->assertSame('A', StringUtils::initials('Alice'));
    }
}
