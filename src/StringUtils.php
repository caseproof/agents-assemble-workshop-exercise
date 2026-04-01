<?php

declare(strict_types=1);

namespace CaseproofUtils;

class StringUtils
{
    /**
     * Convert a string to a URL-friendly slug.
     * Lowercase, replace spaces/special chars with hyphens, collapse multiple hyphens.
     *
     * BUG: Doesn't lowercase the string.
     * BUG: Doesn't collapse multiple hyphens.
     */
    public static function slugify(string $text): string
    {
        // Replace non-alphanumeric characters with hyphens
        $slug = preg_replace('/[^a-zA-Z0-9]+/', '-', $text);

        // Trim hyphens from start and end
        $slug = trim($slug, '-');

        return $slug;
    }

    /**
     * Truncate a string to a maximum length, appending a suffix if truncated.
     * Should not break in the middle of a word.
     *
     * BUG: Breaks in the middle of words instead of at word boundaries.
     * BUG: Doesn't handle case where max length is shorter than suffix.
     */
    public static function truncate(string $text, int $maxLength, string $suffix = '...'): string
    {
        if (mb_strlen($text) <= $maxLength) {
            return $text;
        }

        return mb_substr($text, 0, $maxLength - mb_strlen($suffix)) . $suffix;
    }

    /**
     * Extract initials from a full name.
     * "John Doe" => "JD", "Mary Jane Watson" => "MJW"
     *
     * BUG: Returns lowercase instead of uppercase.
     * BUG: Doesn't handle extra whitespace between names.
     */
    public static function initials(string $name): string
    {
        $parts = explode(' ', $name);
        $result = '';

        foreach ($parts as $part) {
            if ($part !== '') {
                $result .= mb_substr($part, 0, 1);
            }
        }

        return mb_strtolower($result);
    }
}
