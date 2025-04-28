<?php

namespace App;

class Constant
{
    public const DEFAULT_LANGUAGE = 'pt';

    public static function AVAILABLE_LANGUAGES()
    {
        return [
            'pt' => __('Portuguese'),
            'en' => __('English'),
        ];
    }

    public const DEFAULT_CURRENCY = 'BRL';

    public static function AVAILABLE_CURRENCIES()
    {
        return [
            'BRL' => __('Brazilian Real'),
            'USD' => __('United States Dollar'),
            'EUR' => __('Euro'),
            'GBP' => __('British Pound'),
        ];
    }
}
