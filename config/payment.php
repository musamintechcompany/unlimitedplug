<?php

return [
    'default_currency' => 'NGN',
    
    'paystack' => [
        'public_key' => env('PAYSTACK_PUBLIC_KEY'),
        'secret_key' => env('PAYSTACK_SECRET_KEY'),
    ],
    
    'nowpayments' => [
        'api_key' => env('NOWPAYMENTS_API_KEY'),
    ],
    
    // Exchange rates: 1 USD = X NGN
    'exchange_rate' => env('USD_TO_NGN_RATE', 1500),
    'list_exchange_rate' => env('USD_TO_NGN_LIST_RATE', 1500),
    
    'currencies' => [
        'NGN' => [
            'name' => 'Nigerian Naira',
            'symbol' => '₦',
        ],
        'USD' => [
            'name' => 'US Dollar', 
            'symbol' => '$',
        ]
    ]
];