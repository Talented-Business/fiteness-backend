<?php

use Illuminate\Database\Seeder;
use App\Subscription;
use App\Customer;
use App\Transaction;
use App\PaymentPlan;
use App\SubscriptionPlan;
use App\PaymentSubscription;
use App\PaymentTocken;

class CreateSubscription extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer = Customer::whereEmail("degracia.jf@gmail.com")->first();
        $now = $this->makeNewSubscription($customer);
        var_dump($now);
    }
    private function makeNewSubscription($customer){
        $plan = SubscriptionPlan::whereServiceId(1)->whereType('Paid')->first();
        $frequency = 6;
        $coupon = null;
        $paymentPlan = PaymentPlan::createOrUpdate($plan, $customer->id, $coupon, $frequency,'nmi');
        $subscription = Subscription::findOrCreate($customer->id,$plan, $coupon,$frequency,'nmi');
        $paymentSubscription = new PaymentSubscription;
        list($now,$paymentSubscription) = $paymentSubscription->createFromPlan($subscription,$paymentPlan,true);
        $transaction = Transaction::generate($paymentSubscription,$plan,$subscription);
        $nmiCustomerId = time();
        $tocken = new PaymentTocken;
        $tocken->gateway = 'nmi';
        $tocken->holder = 'holder';
        $tocken->tocken = $nmiCustomerId;
        $tocken->customer_id = $customer->id;
        $tocken->last4 = '1111';
        $tocken->expiry_month = 04;
        $tocken->expiry_year = 20;
        $tocken->type = 'visa';
        $tocken->save();
        $transaction->total = 8.99;
        $transaction->status = "Completed";
        $transaction->save();
        $now = $paymentSubscription->updateSubscription($transaction);
        return $now;
    }
}
