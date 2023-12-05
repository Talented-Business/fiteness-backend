<?php
namespace App;

class PayPalPlan
{
    public static function createOrUpdate($servicePlan, $customer, $coupon, $frequency)
    { //$plan,$customer->id,$couponId,$frequency
        if ($customer->user->active == 1) {
            $meta = PaymentPlan::createSlug($servicePlan, $customer->id, $coupon, $frequency,'paypal');
            $subscription = Subscription::whereCustomerId($customer->id)->where(function ($query) use ($servicePlan) {
                $query->wherePlanId($servicePlan->id);
            })->first();
            if ($subscription && $subscription->gateway == 'paypal' && $subscription->payment_plan_id && $subscription->meta == $meta) {
                return $subscription->payment_plan_id;
            } 
            $paymentPlan = PaymentPlan::whereSlug($meta)->first();
            //create
            if($paymentPlan === null){
                $paymentPlan = new PaymentPlan;
                $paymentPlan->slug = $meta;
                $paymentPlan->plan_id = self::create($servicePlan, $customer, $coupon, $frequency);
                $paymentPlan->save();
            }
            return $paymentPlan->plan_id;
        }
        return null;
    }
    private static function getData($servicePlan, $customer, $coupon, $frequency)
    {
        $description = $servicePlan->service->title . ' ' . $frequency;
        if ($frequency > 1) {
            $description .= ' months';
        } else {
            $description .= ' month';
        }
        $regularPrice = $servicePlan['month_' . $frequency];
        if ($coupon && $coupon->status == 'Active') {
            $description .= ' ' . $coupon->description;
            if ($coupon->renewal === 1) {
                if($coupon->form=='%'){
                    $firstSequencePrice = $regularPrice * (100 - $coupon->discount) / 100;
                    $secondSequencePrice = $regularPrice * (100 - $coupon->discount) / 100;
                }else{
                    $firstSequencePrice = $regularPrice  - $coupon->discount;
                    $secondSequencePrice = $regularPrice  - $coupon->discount;
                }
                $description .= ' coupon renewal apply';
            } else {
                if($coupon->form=='%'){
                    $firstSequencePrice = $regularPrice * (100 - $coupon->discount) / 100;    
                }else{
                    $firstSequencePrice = $regularPrice  - $coupon->discount;
                }
                $secondSequencePrice = $regularPrice;
                $description .= ' coupon renewal no';
            }
        } else {
            $firstSequencePrice = $regularPrice;
            $secondSequencePrice = $regularPrice;
        }
        return [$description, $firstSequencePrice, $secondSequencePrice];
    }
    private static function update($paypalPlanId, $servicePlan, $customer, $coupon, $frequency)
    {
        list($description, $firstSequencePrice, $secondSequencePrice) = self::getData($servicePlan, $customer, $coupon, $frequency);
        $pricingSchemes = [
            [
                "billing_cycle_sequence" => 1,
                "pricing_scheme" => [
                    "fixed_price" => [
                        "value" => $firstSequencePrice + 17,
                        "currency_code" => "USD",
                    ],
                ],
            ],
            [
                "billing_cycle_sequence" => 2,
                "pricing_scheme" => [
                    "fixed_price" => [
                        "value" => $secondSequencePrice+1,
                        "currency_code" => "USD",
                    ],
                ],
            ],
        ];
        $client = new \GuzzleHttp\Client();
        list($baseUrl, $clientId, $clientSecret) = PayPalClient::findKeys();
        try{
            $res = $client->post($baseUrl . '/v1/billing/plans/' . $paypalPlanId . '/update-pricing-schemes', [
                'auth' => [$clientId, $clientSecret, 'basic'],
                'headers' => [
                    'Accept-Language' => 'en_US',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'pricing_schemes' => $pricingSchemes,
                ],
            ]);
        }catch (\GuzzleHttp\Exception\RequestException $e) {
            echo \GuzzleHttp\Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo \GuzzleHttp\Psr7\str($e->getResponse());
            }
            die;
        }
        try{
            $res = $client->patch($baseUrl . '/v1/billing/plans/' . $paypalPlanId, [
                'auth' => [$clientId, $clientSecret, 'basic'],
                'headers' => [
                    'Accept-Language' => 'en_US',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    ["op" => "replace",
                    "path" => "/description",
                    "value" => $description,],
                ],
            ]);
        }catch (\GuzzleHttp\Exception\RequestException $e) {
            echo \GuzzleHttp\Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo \GuzzleHttp\Psr7\str($e->getResponse());
            }
            die;
        }
    }
    private static function create($servicePlan, $customer, $coupon, $frequency)
    {
        $intervalUnit = config('app.interval_unit');//'DAY' or "MONTH";
        $paymentPreferences = ['setup_fee' => ['currency_code' => 'USD', 'value' => 0]];
        list($description, $firstSequencePrice, $secondSequencePrice) = self::getData($servicePlan, $customer, $coupon, $frequency);
        $billingCycles = [
            [
                "frequency" => [
                    "interval_unit" => $intervalUnit,
                    "interval_count" => $frequency,
                ],
                "tenure_type" => "TRIAL",
                "sequence" => 1,
                "total_cycles" => 1,
                "pricing_scheme" => [
                    "fixed_price" => [
                        "value" => $firstSequencePrice,
                        "currency_code" => "USD",
                    ],
                ],
            ],
            [
                "frequency" => [
                    "interval_unit" => $intervalUnit,
                    "interval_count" => $frequency,
                ],
                "tenure_type" => "REGULAR",
                "sequence" => 2,
                "total_cycles" => 0,
                "pricing_scheme" => [
                    "fixed_price" => [
                        "value" => $secondSequencePrice,
                        "currency_code" => "USD",
                    ],
                ],
            ],
        ];
        if (true) {
            $client = new \GuzzleHttp\Client();
            list($baseUrl, $clientId, $clientSecret) = PayPalClient::findKeys();
            try{
                $res = $client->post($baseUrl . '/v1/billing/plans', [
                    'auth' => [$clientId, $clientSecret, 'basic'],
                    'headers' => [
                        'Accept-Language' => 'en_US',
                        'Accept' => 'application/json',
                    ],
                    'json' => [
                        'product_id' => $servicePlan->service->paypal_product_id,
                        'name' => $servicePlan->service->title,
                        'description' => $description,
                        'billing_cycles' => $billingCycles,
                        'payment_preferences' => $paymentPreferences,
                    ],
                ]);
                $data = json_decode($res->getBody(), true);
            }catch (\GuzzleHttp\Exception\RequestException $e) {
                echo \GuzzleHttp\Psr7\str($e->getRequest());
                if ($e->hasResponse()) {
                    echo \GuzzleHttp\Psr7\str($e->getResponse());
                }
                die;
            }
            $paypalPlanId = $data['id'];
        }
        return $paypalPlanId;
    }
}
