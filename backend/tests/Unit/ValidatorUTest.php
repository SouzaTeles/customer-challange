<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Utils\Validator;
use PHPUnit\Framework\TestCase;

final class ValidatorUTest extends TestCase
{
    public function testIsValidCpfWithValidCpf(): void
    {
        $this->assertTrue(Validator::isValidCpf('12345678909'));
        $this->assertTrue(Validator::isValidCpf('111.444.777-35'));
        $this->assertTrue(Validator::isValidCpf('529.982.247-25'));
    }

    public function testIsValidCpfWithInvalidLength(): void
    {
        $this->assertFalse(Validator::isValidCpf('123'));
        $this->assertFalse(Validator::isValidCpf('123456789012'));
        $this->assertFalse(Validator::isValidCpf(''));
    }

    public function testIsValidCpfWithRepeatedDigits(): void
    {
        $this->assertFalse(Validator::isValidCpf('00000000000'));
        $this->assertFalse(Validator::isValidCpf('11111111111'));
        $this->assertFalse(Validator::isValidCpf('22222222222'));
        $this->assertFalse(Validator::isValidCpf('99999999999'));
    }

    public function testIsValidCpfWithInvalidVerifierDigits(): void
    {
        $this->assertFalse(Validator::isValidCpf('12345678900'));
        $this->assertFalse(Validator::isValidCpf('12345678901'));
        $this->assertFalse(Validator::isValidCpf('111.444.777-00'));
    }

    public function testIsValidCpfWithFormatting(): void
    {
        $this->assertTrue(Validator::isValidCpf('123.456.789-09'));
        $this->assertTrue(Validator::isValidCpf('123-456-789-09'));
        $this->assertTrue(Validator::isValidCpf('123 456 789 09'));
    }

    public function testIsValidEmailWithValidEmails(): void
    {
        $this->assertTrue(Validator::isValidEmail('test@example.com'));
        $this->assertTrue(Validator::isValidEmail('user.name@domain.co.uk'));
        $this->assertTrue(Validator::isValidEmail('user+tag@example.com'));
    }

    public function testIsValidEmailWithInvalidEmails(): void
    {
        $this->assertFalse(Validator::isValidEmail('invalid'));
        $this->assertFalse(Validator::isValidEmail('invalid@'));
        $this->assertFalse(Validator::isValidEmail('@example.com'));
        $this->assertFalse(Validator::isValidEmail('invalid@.com'));
        $this->assertFalse(Validator::isValidEmail(''));
    }

    public function testIsValidDateWithValidDates(): void
    {
        $this->assertTrue(Validator::isValidDate('2024-01-15'));
        $this->assertTrue(Validator::isValidDate('1990-12-31'));
        $this->assertTrue(Validator::isValidDate('2000-02-29'));
    }

    public function testIsValidDateWithInvalidDates(): void
    {
        $this->assertFalse(Validator::isValidDate('2024-13-01'));
        $this->assertFalse(Validator::isValidDate('2024-02-30'));
        $this->assertFalse(Validator::isValidDate('invalid'));
        $this->assertFalse(Validator::isValidDate(''));
    }

    public function testIsValidDateWithCustomFormat(): void
    {
        $this->assertTrue(Validator::isValidDate('15/01/2024', 'd/m/Y'));
        $this->assertTrue(Validator::isValidDate('01-15-2024', 'm-d-Y'));
        $this->assertFalse(Validator::isValidDate('2024-01-15', 'd/m/Y'));
    }

    public function testIsValidPhoneWithValidPhones(): void
    {
        $this->assertTrue(Validator::isValidPhone('1234567890'));
        $this->assertTrue(Validator::isValidPhone('12345678901'));
        $this->assertTrue(Validator::isValidPhone('(11) 98765-4321'));
        $this->assertTrue(Validator::isValidPhone('11 9 8765-4321'));
    }

    public function testIsValidPhoneWithInvalidPhones(): void
    {
        $this->assertFalse(Validator::isValidPhone('123'));
        $this->assertFalse(Validator::isValidPhone('123456789'));
        $this->assertFalse(Validator::isValidPhone('123456789012'));
        $this->assertFalse(Validator::isValidPhone(''));
    }

    public function testIsValidCepWithValidCeps(): void
    {
        $this->assertTrue(Validator::isValidCep('12345678'));
        $this->assertTrue(Validator::isValidCep('12345-678'));
        $this->assertTrue(Validator::isValidCep('01310-100'));
    }

    public function testIsValidCepWithInvalidCeps(): void
    {
        $this->assertFalse(Validator::isValidCep('1234567'));
        $this->assertFalse(Validator::isValidCep('123456789'));
        $this->assertFalse(Validator::isValidCep(''));
    }
}
