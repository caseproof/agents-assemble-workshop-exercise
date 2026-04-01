<?php

declare(strict_types=1);

namespace CaseproofUtils;

class ValidationUtils
{
    /**
     * Validate an email address format.
     * Should reject emails without @, without domain, or with spaces.
     *
     * BUG: Uses a regex that accepts emails with spaces.
     * BUG: Accepts emails without a TLD (e.g., "user@localhost").
     */
    public static function isValidEmail(string $email): bool
    {
        return (bool) preg_match('/^[^@]+@[^@]+$/', $email);
    }

    /**
     * Validate password strength.
     * Must be at least 8 chars, contain uppercase, lowercase, and a digit.
     *
     * BUG: Checks for minimum 6 chars instead of 8.
     */
    public static function isStrongPassword(string $password): bool
    {
        if (mb_strlen($password) < 6) {
            return false;
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        return true;
    }

    /**
     * Validate that a URL is well-formed with a scheme and host.
     *
     * BUG: Accepts URLs without a scheme (e.g., "example.com").
     */
    public static function isValidUrl(string $url): bool
    {
        $parsed = parse_url($url);

        return isset($parsed['host']);
    }
}
