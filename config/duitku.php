<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Duitku Credentials
    |--------------------------------------------------------------------------
    */
    'merchant_code' => env('DUITKU_MERCHANT_CODE'),
    'api_key' => env('DUITKU_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    | Set to true for sandbox, false for production
    */
    'sandbox' => env('DUITKU_SANDBOX', true),

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    */
    'endpoints' => [
        'sandbox' => [
            'base_url' => 'https://sandbox.duitku.com/webapi/api/merchant',
            'inquiry' => 'https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry',
            'payment_methods' => 'https://sandbox.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod',
            'check_status' => 'https://sandbox.duitku.com/webapi/api/merchant/transactionStatus',
        ],
        'production' => [
            'base_url' => 'https://passport.duitku.com/webapi/api/merchant',
            'inquiry' => 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry',
            'payment_methods' => 'https://passport.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod',
            'check_status' => 'https://passport.duitku.com/webapi/api/merchant/transactionStatus',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Callback URLs
    |--------------------------------------------------------------------------
    */
    'callback_url' => env('DUITKU_CALLBACK_URL', '/webhook/duitku'),
    'return_url' => env('DUITKU_RETURN_URL', '/checkout/return'),

    /*
    |--------------------------------------------------------------------------
    | Payment Expiry (in minutes)
    |--------------------------------------------------------------------------
    */
    'expiry_period' => env('DUITKU_EXPIRY_PERIOD', 1440), // 24 hours default
];
