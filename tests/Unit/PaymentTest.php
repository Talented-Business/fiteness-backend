<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\BankTransferRequest;
use App\Subscription;
use App\SubscriptionPlan;
use App\PaymentPlan;
use App\Payment\Bank;

class PaymentTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
    public function testBank(){
        $request = BankTransferRequest::find(2);
        $subscription = Subscription::whereCustomerId($request->customer_id)->wherePlanId($request->plan_id)->first();
        if(!$subscription)$subscription = new Subscription;
        $subscription->frequency = $request->frequency;
        $servicePlan = SubscriptionPlan::find($request->plan_id);            
        $paymentPlan = PaymentPlan::createOrUpdate($servicePlan, $request->customer_id, $request->coupon, $subscription->convertMonths($request->frequency),'bank');
        var_dump($paymentPlan->slug);
        var_dump($paymentPlan->id);
        $this->assertTrue(true);
    }
    public function testEndDate(){
        $request = BankTransferRequest::find(2);
        $subscription = Subscription::whereCustomerId($request->customer_id)->wherePlanId($request->plan_id)->first();
        if($subscription){
            // Bank::scraping($this);
            $time =  Bank::getEndDate($subscription);
            // $endDate = "2020-12-23 01:00:00";
            // $timeDiff = round((time() - strtotime($endDate))/3600);
            // print_r($timeDiff);
        }
        $this->assertTrue(true);
    }
    public function testScrape(){
        $request = BankTransferRequest::find(7);
        $subscription = Subscription::whereCustomerId($request->customer_id)->wherePlanId($request->plan_id)->first();
        if($subscription){
            Bank::scrape($subscription);
            $subscription->refresh();
            var_dump($subscription->reminder_before_seven);
        }
        $this->assertTrue(true);
    }
}
