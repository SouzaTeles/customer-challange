<?php

declare(strict_types=1);

namespace App\Utils;

use DateTime;

final class Validator
{
    private const PATTERN_ONLY_NUMBERS = '/[^0-9]/';
    private const PATTERN_REPEATED_DIGITS = '/(\d)\1{10}/';

    public static function isValidCpf(string $cpf): bool
    {
        $cpf = preg_replace(self::PATTERN_ONLY_NUMBERS, '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match(self::PATTERN_REPEATED_DIGITS, $cpf)) {
            return false;
        }

        return self::validateVerifierDigits($cpf);
    }

    private static function validateVerifierDigits(string $cpf): bool
    {
        if ($cpf[9] != self::calculateVerifierDigit($cpf, 9)) {
            return false;
        }
        
        if ($cpf[10] != self::calculateVerifierDigit($cpf, 10)) {
            return false;
        }
        
        return true;
    }

    private static function calculateVerifierDigit(string $cpf, int $digitPosition): int
    {
        $sum = 0;
        for ($index = 0; $index < $digitPosition; $index++) {
            $multiplier = ($digitPosition + 1) - $index;
            $sum += $cpf[$index] * $multiplier;
        }
        return ((10 * $sum) % 11) % 10;
    }

    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function isValidDate(string $date, string $format = 'Y-m-d'): bool
    {
        $dateTime = DateTime::createFromFormat($format, $date);
        return $dateTime && $dateTime->format($format) === $date;
    }

    public static function isValidPhone(string $phone): bool
    {
        $phone = preg_replace(self::PATTERN_ONLY_NUMBERS, '', $phone);
        return strlen($phone) >= 10 && strlen($phone) <= 11;
    }

    public static function isValidCep(string $cep): bool
    {
        $cep = preg_replace(self::PATTERN_ONLY_NUMBERS, '', $cep);
        return strlen($cep) === 8;
    }
}
