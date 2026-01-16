<?php

return [
    'default_currency' => 'USD',
    
    'paystack' => [
        'public_key' => env('PAYSTACK_PUBLIC_KEY'),
        'secret_key' => env('PAYSTACK_SECRET_KEY'),
    ],
    
    'flutterwave' => [
        'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
        'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
        'encryption_key' => env('FLUTTERWAVE_ENCRYPTION_KEY'),
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
    
    'payment_gateways' => [
        'NGN' => 'paystack',
        'default' => 'flutterwave'
    ]
];