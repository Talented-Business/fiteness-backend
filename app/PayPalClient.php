<?php
namespace App;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

class PayPalClient
{
    /**
     * Returns PayPal HTTP client instance with environment which has access
     * credentials context. This can be used invoke PayPal API's provided the
     * credentials have the access to do so.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    public static function apiContext()
    {
        $clientId = config('paypal.client_id');
        $clientSecret = config('paypal.secret');
        new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $clientId,
                $clientSecret
            )
        );                  
    }
    public static function findKeys(){
        $testMode = config('app.payment_test_mode');
        $clientId = config('paypal.client_id');
        $clientSecret = config('paypal.secret');
        if($testMode){
            $baseUrl = 'https://api.sandbox.paypal.com';
        }else{
            $baseUrl = 'https://api.paypal.com';
        }
        return [$baseUrl,$clientId,$clientSecret];
    }
    /**
     * Setting up and Returns PayPal SDK environment with PayPal Access credentials.
     * For demo purpose, we are using SandboxEnvironment. In production this will be
     * ProductionEnvironment.
     */
    public static function environment()
    {
        $testMode = config('app.payment_test_mode');
        $clientId = config('paypal.client_id');
        $clientSecret = config('paypal.secret');
        if($testMode){
            return new SandboxEnvironment($clientId, $clientSecret);
        }else{
            return new ProductionEnvironment($clientId, $clientSecret);
        }
    }
}