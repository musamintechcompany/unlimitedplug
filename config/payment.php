<?php

return [
    'default_currency' => 'USD',
    
    'flutterwave' => [
        'public_key' => env('APP_ENV') === 'production' 
            ? env('FLUTTERWAVE_LIVE_PUBLIC_KEY') 
            : env('FLUTTERWAVE_TEST_PUBLIC_KEY'),
        'secret_key' => env('APP_ENV') === 'production' 
            ? env('FLUTTERWAVE_LIVE_SECRET_KEY') 
            : env('FLUTTERWAVE_TEST_SECRET_KEY'),
        'encryption_key' => env('APP_ENV') === 'production' 
            ? env('FLUTTERWAVE_LIVE_ENCRYPTION_KEY') 
            : env('FLUTTERWAVE_TEST_ENCRYPTION_KEY'),
    ],
    
    'currencies' => [
        // Major Global Currencies
        'USD' => ['name' => 'US Dollar', 'symbol' => '$'],
        'EUR' => ['name' => 'Euro', 'symbol' => '€'],
        'GBP' => ['name' => 'British Pound', 'symbol' => '£'],
        'CAD' => ['name' => 'Canadian Dollar', 'symbol' => 'C$'],
        'AUD' => ['name' => 'Australian Dollar', 'symbol' => 'A$'],
        'JPY' => ['name' => 'Japanese Yen', 'symbol' => '¥'],
        'CHF' => ['name' => 'Swiss Franc', 'symbol' => 'CHF'],
        
        // African Currencies
        'NGN' => ['name' => 'Nigerian Naira', 'symbol' => '₦'],
        'GHS' => ['name' => 'Ghanaian Cedi', 'symbol' => 'GH₵'],
        'KES' => ['name' => 'Kenyan Shilling', 'symbol' => 'KSh'],
        'UGX' => ['name' => 'Ugandan Shilling', 'symbol' => 'USh'],
        'TZS' => ['name' => 'Tanzanian Shilling', 'symbol' => 'TSh'],
        'ZAR' => ['name' => 'South African Rand', 'symbol' => 'R'],
        'XAF' => ['name' => 'Central African CFA Franc', 'symbol' => 'FCFA'],
        'XOF' => ['name' => 'West African CFA Franc', 'symbol' => 'CFA'],
        'RWF' => ['name' => 'Rwandan Franc', 'symbol' => 'FRw'],
        'ZMW' => ['name' => 'Zambian Kwacha', 'symbol' => 'ZK'],
        
        // Middle East & Asia
        'AED' => ['name' => 'UAE Dirham', 'symbol' => 'د.إ'],
        'SAR' => ['name' => 'Saudi Riyal', 'symbol' => 'SR'],
        'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹'],
        'CNY' => ['name' => 'Chinese Yuan', 'symbol' => '¥'],
        
        // Latin America
        'BRL' => ['name' => 'Brazilian Real', 'symbol' => 'R$'],
        'MXN' => ['name' => 'Mexican Peso', 'symbol' => 'MX$'],
    ],
    
    // Country to Currency mapping for auto-detection
    'country_currency_map' => [
        'US' => 'USD', 'CA' => 'CAD', 'GB' => 'GBP', 'AU' => 'AUD', 'NZ' => 'AUD',
        'NG' => 'NGN', 'GH' => 'GHS', 'KE' => 'KES', 'UG' => 'UGX', 'TZ' => 'TZS',
        'ZA' => 'ZAR', 'RW' => 'RWF', 'ZM' => 'ZMW',
        'AE' => 'AED', 'SA' => 'SAR', 'IN' => 'INR', 'CN' => 'CNY', 'JP' => 'JPY',
        'BR' => 'BRL', 'MX' => 'MXN', 'CH' => 'CHF',
        // European countries using EUR
        'DE' => 'EUR', 'FR' => 'EUR', 'IT' => 'EUR', 'ES' => 'EUR', 'NL' => 'EUR',
        'BE' => 'EUR', 'AT' => 'EUR', 'PT' => 'EUR', 'IE' => 'EUR', 'GR' => 'EUR',
        // West African CFA countries
        'SN' => 'XOF', 'CI' => 'XOF', 'BJ' => 'XOF', 'TG' => 'XOF', 'BF' => 'XOF',
        'ML' => 'XOF', 'NE' => 'XOF', 'GW' => 'XOF',
        // Central African CFA countries
        'CM' => 'XAF', 'GA' => 'XAF', 'CG' => 'XAF', 'TD' => 'XAF', 'CF' => 'XAF', 'GQ' => 'XAF',
    ],
    
    'payment_gateways' => [
        'default' => 'flutterwave'
    ]
];