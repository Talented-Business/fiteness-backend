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
        $testMode = env('PAYMENT_TEST_MODE');
        if($testMode){
            $clientId = env('PAYPAL_SANDBOX_CLIENT_ID');
            $clientSecret = env('PAYPAL_SANDBOX_SECURITY_KEY');
        }else{
            $clientId = env('PAYPAL_CLIENT_ID');
            $clientSecret = env('PAYPAL_SECURITY_KEY');
        }
        new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $clientId,
                $clientSecret
            )
        );                  
    }
    public static function findKeys(){
        $testMode = env('PAYMENT_TEST_MODE');
        if($testMode){
            $clientId = env('PAYPAL_SANDBOX_CLIENT_ID');
            $clientSecret = env('PAYPAL_SANDBOX_SECURITY_KEY');
            $baseUrl = 'https://api.sandbox.paypal.com';
        }else{
            $clientId = env('PAYPAL_CLIENT_ID');
            $clientSecret = env('PAYPAL_SECURITY_KEY');
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
        $testMode = env('PAYMENT_TEST_MODE');
        if($testMode){
            $clientId = env('PAYPAL_SANDBOX_CLIENT_ID');
            $clientSecret = env('PAYPAL_SANDBOX_SECURITY_KEY');
            return new SandboxEnvironment($clientId, $clientSecret);
        }else{
            $clientId = env('PAYPAL_CLIENT_ID');
            $clientSecret = env('PAYPAL_SECURITY_KEY');
            return new ProductionEnvironment($clientId, $clientSecret);
        }
    }
}