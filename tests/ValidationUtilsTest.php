<?php

declare(strict_types=1);

namespace CaseproofUtils\Tests;

use PHPUnit\Framework\TestCase;
use CaseproofUtils\ValidationUtils;

class ValidationUtilsTest extends TestCase
{
    // ── isValidEmail ──

    public function testValidEmailAcceptsGoodEmails(): void
    {
        $this->assertTrue(ValidationUtils::isValidEmail('user@example.com'));
        $this->assertTrue(ValidationUtils::isValidEmail('test.name@domain.co.uk'));
    }

    public function testValidEmailRejectsNoAt(): void
    {
        $this->assertFalse(ValidationUtils::isValidEmail('userexample.com'));
    }

    public function testValidEmailRejectsSpaces(): void
    {
        $this->assertFalse(ValidationUtils::isValidEmail('user @example.com'));
        $this->assertFalse(ValidationUtils::isValidEmail('user@ example.com'));
    }

    public function testValidEmailRejectsNoTld(): void
    {
        $this->assertFalse(ValidationUtils::isValidEmail('user@localhost'));
    }

    // ── isStrongPassword ──

    public function testStrongPasswordAcceptsValid(): void
    {
        $this->assertTrue(ValidationUtils::isStrongPassword('MyPass123'));
        $this->assertTrue(ValidationUtils::isStrongPassword('Abcdefg1'));
    }

    public function testStrongPasswordRejectsTooShort(): void
    {
        // "Short1" is 6 chars — should fail because minimum is 8
        $this->assertFalse(ValidationUtils::isStrongPassword('Short1'));
    }

    public function testStrongPasswordRejectsSevenChars(): void
    {
        // "Shorty1" is 7 chars — should fail because minimum is 8
        $this->assertFalse(ValidationUtils::isStrongPassword('Shorty1'));
    }

    public function testStrongPasswordRejectsNoUppercase(): void
    {
        $this->assertFalse(ValidationUtils::isStrongPassword('lowercase1'));
    }

    public function testStrongPasswordRejectsNoDigit(): void
    {
        $this->assertFalse(ValidationUtils::isStrongPassword('NoDigitsHere'));
    }

    // ── isValidUrl ──

    public function testValidUrlAcceptsHttps(): void
    {
        $this->assertTrue(ValidationUtils::isValidUrl('https://example.com'));
        $this->assertTrue(ValidationUtils::isValidUrl('https://example.com/path?q=1'));
    }

    public function testValidUrlAcceptsHttp(): void
    {
        $this->assertTrue(ValidationUtils::isValidUrl('http://example.com'));
    }

    public function testValidUrlRejectsNoScheme(): void
    {
        // "example.com" without a scheme should be invalid
        $this->assertFalse(ValidationUtils::isValidUrl('example.com'));
    }

    public function testValidUrlRejectsRandomString(): void
    {
        $this->assertFalse(ValidationUtils::isValidUrl('not a url at all'));
    }
}
