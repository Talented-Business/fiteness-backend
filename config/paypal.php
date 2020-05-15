<?php 
return [ 
    'client_id' => env('PAYMENT_TEST_MODE')?env('PAYPAL_SANDBOX_CLIENT_ID'):env('PAYPAL_CLIENT_ID'),
    'secret' => env('PAYMENT_TEST_MODE')?env('PAYPAL_SANDBOX_SECURITY_KEY'):env('PAYPAL_SECURITY_KEY'),
    'settings' => array(
        'mode' => env('PAYMENT_TEST_MODE')?'sandbox':'live',
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),
];