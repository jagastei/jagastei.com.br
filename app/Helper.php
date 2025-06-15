<?php

declare(strict_types=1);

namespace App;

use Akaunting\Money\Money;
use Exception;

final class Helper
{
    public static function formatMoney(int $value, $currency = Constant::DEFAULT_CURRENCY): string
    {
        if (! isset(Constant::AVAILABLE_CURRENCIES()[$currency])) {
            throw new Exception('Invalid currency');
        }

        return Money::{$currency}($value)->format();
    }

    public static function extractNumbersFromString(mixed $value, bool $forceInteger = false, bool $forceFloat = false): null|int|float|string
    {
        if (is_bool($value)) {
            return null;
        }

        if (blank($value)) {
            return null;
        }

        if (blank($value)) {
            return null;
        }

        if ($forceInteger) {
            $value = preg_replace('/\D/', '', $value);

            return (int) $value;
        }

        if ($forceFloat) {
            $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_ALLOW_FRACTION);

            return (float) $value;
        }

        $value = preg_replace('/\D/', '', $value);

        return $value;
    }
}
