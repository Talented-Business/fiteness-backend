<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Subscription;
use App\PaymentSubscription;
use App\Transaction;
use App\Customer;
use App\Coupon;
use App\Record;
use App\User;
use App\Setting;
use App\Cart;
use App\PaymentPlan;
use App\SubscriptionPlan;
use App\PaymentTocken;
use App\Jobs\NotifyNonSubscriber;
use Mail;
use App\Mail\VerifyMail;
use App\Mail\SubscriptionCancelAdmin;
use App\Mail\BankRequest;
use App\SurveyReport;
use App\Workout;
use App\StaticWorkout;
use App\Payment\Bank;
use App\BankTransferRequest;

class FindTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(false){
            $subscriptions = Subscription::all();
            foreach($subscriptions as $subscription){
                $subscription->findAllTransactionsWithPayPal();
            }
        }
        if(false){
            $paymentSubscription = PaymentSubscription::find(22);
            $paymentSubscription->forceRenewalNmi();
        }
        if(false){
            $transaction = Transaction::find(1);
            $paymentSubscription = PaymentSubscription::whereSubscriptionId($transaction->payment_subscription_id)->first();
            if($paymentSubscription)$paymentSubscription->renewalSendMail($transaction);
        }
        if(false){
            $transaction = Transaction::find(1452);
            $paymentSubscription = PaymentSubscription::whereSubscriptionId($transaction->payment_subscription_id)->first();
            if($paymentSubscription)$paymentSubscription->sendFirstMail($transaction);
        }
        if(false){
            $email = "sui201837@gmail.com";
            $verifyEmail = "sui201837@outlook.com";
            $result = Customer::verifyEmail($email);
            print_r($result);
        }
        if(false){
            $customer = Customer::find(25);
            $customer->changeCoupon(10);
        }
        if(false){
            $customers = Customer::all();
            foreach($customers as $customer){
                $userTimezone = new \DateTimeZone($customer->timezone);
                $objDateTime = new \DateTime('NOW');
                $objDateTime->setTimezone($userTimezone);
                $fiveHours = \DateInterval::createFromDateString('+5 hours');
                $objDateTime->add($fiveHours);
                echo $objDateTime->format('Y-m-d');
                echo $customer->timezone;
                echo "\n";
            }
        }
        if(false){
            if(PHP_OS == 'Linux'){
                echo storage_path();
            }
            echo "\n";
        }
        if(false){
            $user = User::whereEmail('sui201837@outlook.com')->first();
            print_r($user->customer->current_condition);
            print_r($user->customer->objective);
            if($user)Mail::to($user->email)->send(new VerifyMail($user));
        }
        if(false){
            //Cart::whereCustomerId(32)->delete();
            $cart = Cart::find(1);
            if($cart){
                $cartAbandonSetting = Setting::getCart();
                $cart->sendAbandonMail($cartAbandonSetting);
            }
        }
        if(false){
            $customer = Customer::find(32);
            if($customer){
                //$customer->findRecord()->benckmark_count = 1;
                //$customer->record->save();
                $colmun = 'benckmark_count';
                $customer = $customer->increaseRecord($colmun);
            }
        }
        if(false){
            $customer = Customer::whereEmail('sui201865@outlook.com')->first();
            NotifyNonSubscriber::dispatch($customer, new \App\Mail\NotifyNonSubscriber($customer))->delay(now()->addMinutes(1));
        }
        if(false){
            $subscriptions = Subscription::all();
            foreach($subscriptions as $subscription){
                if($subscription->customer->first_payment_date){
                    if($subscription->transaction->status == 'Completed' && $subscription->transaction->total==0){
                        $subscription->customer->first_payment_date = null;
                        $subscription->customer->save();
                    }
                }
            }
        }
        if(false){
            $customer = Customer::whereEmail("sui201837@gmail.com")->first();
            $now = $this->makeNewSubscription($customer);
            var_dump($now);    
        }
        if(false){
            exec("ps aux | grep -i queue:work", $output, $return);
            $queue = 0;
            if(is_array($output)){
                foreach($output as $value){
                    if(strpos($value,'artisan queue:work')){
                        print_r($value);
                        print_r("\n");
                        $queue++;
                    }
                }
            }
            if($queue!=2){
                exec("pgrep supervisord", $output1, $return1);
                if ($return1 == 0) {
                    echo "Ok, process is running\n";
                    var_dump($output1);
                    if(isset($output1[0])){
                        $pid = $output1[0];
                        exec("kill -s SIGTERM ".$pid, $output, $return);
                    }
                }else{
                    exec("/home/fitemosc/.local/bin/supervisord -c /home/fitemosc/.local/bin/laravel-dev.conf", $output2, $return2);
                    var_dump($output2);
                }
            }
        }
        if(false){
            $time = 1589413708;
            echo date("Y-m-d H:i:s",$time);
        }
        if(false){
            $subscriptions = Subscription::whereStatus('Cancelled')->get();
            foreach($subscriptions as $subscription){
                $paymentSubscription = $subscription->getLastPaymentSubscription();
                if($paymentSubscription->status != 'Cancelled'){
                    print_r($subscription->customer_id);
                    print_r($paymentSubscription->status);
                    print_r("\n");
                }
            }
        }
        if(false){
            $customer = Customer::whereEmail('degracia.jf@gmail.com')->first();
            if($customer){
                $workout = $customer->getSendableWorkout(1);
                if($workout){
                    try{
                        $customer->send($workout);
                    }catch(\Exception $e){
                        //print_r($e);
                    }
                }
            }
        }
        if(false){
            $subscription = Subscription::find(1021);
            $time = $subscription->nextPaymentTime();
            print_r($time);
        }
        if(false){
            $transaction = Transaction::find(468);
            $nextdatetime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($transaction->done_date)) . " +1 month"));
            print_r($nextdatetime);
            print_r("\n");
            print_r(date("Y-m-d H:i:s"));
        }
        if(false){
            $ids = [3994,3995,3997];
            foreach($ids as $id){
                $transaction = Transaction::whereCustomerId($id)->first();
                $paymentSubscription = PaymentSubscription::whereCustomerId($id)->first();
                $now = $paymentSubscription->updateSubscription($transaction);
                $paymentSubscription->sendFirstMail($transaction);
            }
        }
        if(false){
            $paymentSubscription = PaymentSubscription::whereSubscriptionId("nmi-2-3194-1-6-1588793005")->first();
            var_dump(method_exists($paymentSubscription,'cancelChangedPaymentSubscriptions'));
            $paymentSubscription->cancelChangedPaymentSubscriptions();
        }
        if(false){
            $this->sendFirstFreeEmail();
        }
        if(false){
            $this->payFreeFirst();
        }
        if(false){
            $this->setFriend();
        }
        if(false){
            $this->removeFriend();
        }
        if(false){
            $this->nextPaymentAmount();
        }
        if(false){
            $this->currentWorkoutPeriod();
        }
        if(false){
            $this->getImageSizes();
        }
        if(false){
            $this->surveyExport();
        }
        if(false){
            $this->loginActivity();
        }
        if(false){
            $this->findActiveCustomersWithoutCreditCard();
        }
        if(false){
            $this->findTransactionsWithoutCreditCard();
        }
        if(false){
            $this->customerSetfreeSuscription();
        }
        if(false){
            $this->bankRequest();
        }
        if(false){
            $this->updateShortCodes();
        }
        if(true){
            // $this->updateWorkout();
            $this->updateStaticWorkout();
        }
        if(false){
            $this->bankReminder();
        }
    }
    private function bankReminder(){
        $request = BankTransferRequest::find(7);
        $subscription = Subscription::whereCustomerId($request->customer_id)->wherePlanId($request->plan_id)->first();
        if($subscription){
            Bank::scrape($subscription);
        }
    }
    private function updateWorkout(){
        $workouts = Workout::all();
        foreach($workouts as $workout){
            if($workout->comentario)$workout->comentario_element = serialize($workout->convertContent($workout->comentario));
            if($workout->calentamiento)$workout->calentamiento_element = serialize($workout->convertContent($workout->calentamiento));
            if($workout->con_content)$workout->con_content_element = serialize($workout->convertContent($workout->con_content));
            if($workout->sin_content)$workout->sin_content_element = serialize($workout->convertContent($workout->sin_content));
            if($workout->strong_male)$workout->strong_male_element = serialize($workout->convertContent($workout->strong_male));
            if($workout->strong_female)$workout->strong_female_element = serialize($workout->convertContent($workout->strong_female));
            if($workout->fit)$workout->fit_element = serialize($workout->convertContent($workout->fit));
            if($workout->cardio)$workout->cardio_element = serialize($workout->convertContent($workout->cardio));
            if($workout->extra_sin)$workout->extra_sin_element = serialize($workout->convertContent($workout->extra_sin));
            if($workout->activo)$workout->activo_element = serialize($workout->convertContent($workout->activo));
            if($workout->blog)$workout->blog_element = serialize($workout->convertContent($workout->blog));
            $workout->save();
        }
    }
    private function updateStaticWorkout(){
        $workouts = StaticWorkout::all();
        foreach($workouts as $workout){
            if($workout->comentario)$workout->comentario_element = serialize($workout->convertContent($workout->comentario));
            if($workout->calentamiento)$workout->calentamiento_element = serialize($workout->convertContent($workout->calentamiento));
            if($workout->con_content)$workout->con_content_element = serialize($workout->convertContent($workout->con_content));
            if($workout->sin_content)$workout->sin_content_element = serialize($workout->convertContent($workout->sin_content));
            if($workout->strong_male)$workout->strong_male_element = serialize($workout->convertContent($workout->strong_male));
            if($workout->strong_female)$workout->strong_female_element = serialize($workout->convertContent($workout->strong_female));
            if($workout->fit)$workout->fit_element = serialize($workout->convertContent($workout->fit));
            if($workout->cardio)$workout->cardio_element = serialize($workout->convertContent($workout->cardio));
            if($workout->extra_sin)$workout->extra_sin_element = serialize($workout->convertContent($workout->extra_sin));
            if($workout->activo)$workout->activo_element = serialize($workout->convertContent($workout->activo));
            if($workout->blog)$workout->blog_element = serialize($workout->convertContent($workout->blog));
            $workout->save();
        }
    }
    private function updateShortCodes(){
        $shortCodes = \App\ShortCode::whereStatus('Active')->get();
        $ids = [];
        foreach($shortCodes as $shortCode){
            $ids[] = $shortCode->id;
        }
        foreach($shortCodes as $shortCode){
            $alternateA = $ids[rand(0,count($ids)-1)];
            $alternateB = $ids[rand(0,count($ids)-1)];
            if($shortCode->id != $alternateA && $alternateA!=$alternateB && $shortCode->id != $alternateB){
                $shortCode->alternate_a = $alternateA;
                $shortCode->multipler_a = 2;
                $shortCode->alternate_b = $alternateB;
                $shortCode->multipler_b = 1.5;
                $shortCode->save();
            }
        }
    }
    private function bankRequest(){
        $user = User::find(5896);
        $duration = 3;
        $amount = "8.99";
        $bankFee = "1.23";
        Mail::to($user->email)->send(new BankRequest($user->customer,$duration,$amount, $bankFee));        
    }
    private function customerSetfreeSuscription(){
        $customer = Customer::find(5751);
        $customer->setFreeSubscription();
    }
    private function findTransactionsWithoutCreditCard(){
        $from = 2511;
        $to = 2531;
        for($i=$from;$i<=$to;$i++){
            $transaction = Transaction::find($i);
            if($transaction){
                $subscription = Subscription::whereCustomerId($transaction->customer_id)->first();
                if($subscription->status == "Active"){
                    print_r($subscription->customer_id);
                    //print_r("Subscription");
                    print_r("\n");
                }else{
                    //print_r($i);
                    //print_r("Transaction");
                    //print_r("\n");
                }
            }
        }
    }
    private function findActiveCustomersWithoutCreditCard(){
        $subscriptions = Subscription::whereStatus("Active")->get();
        foreach( $subscriptions as $subscription){
            $tokens = PaymentTocken::whereCustomerId($subscription->customer_id)->get();
             if(count($tokens) == 0){
                print_r($subscription->end_date);
                print_r("********");
                print_r($subscription->customer_id);
                print_r("\n");
             }
        }
    }
    private function loginActivity(){
        $customer = Customer::find(5);
        \App\Jobs\Activity::dispatch($customer,'2020-08-31');
    }
    private function surveyExport(){
        $surveyReports = new SurveyReport;
        $export = $surveyReports->findAnswersExport(1);
    }
    private function getImageSizes(){
        $sizes = Setting::IMAGE_SIZES;
        $sizes = array_values($sizes);
        $y = [];
        foreach($sizes as $size){
            $s = explode('X',$size);
            $y[] = $s;
        }
        print_r($y);
    }
    private function currentWorkoutPeriod(){
        $subscription = Subscription::find(760);
        //$text = $subscription->currentWorkoutPeriod();
        $text = $subscription->nextWorkoutPlan();
        print_r($text);
    }
    private function nextPaymentAmount(){
        $paymentSubscription = PaymentSubscription::find(972);
        $referralCoupon = Coupon::find(60);
        $amount = $paymentSubscription->nextPaymentAmount($referralCoupon);
        print_r($amount);
    }
    private function removeFriend(){
        $customer = Customer::whereEmail("rudy.ralison@arneg.com.pa")->first();
        if($customer)$customer->removeFriendShip();
    }
    private function payFreeFirst(){
        $subscription = Subscription::find(751);
        $subscription->scrapingFree();
    }
    private function sendFirstFreeEmail(){
        $subscription = Subscription::find(751);
        $paymentSubscription = PaymentSubscription::wherePlanId($subscription->payment_plan_id)->first();
        if($paymentSubscription)$paymentSubscription->sendFirstFreeMail($subscription);
    }
    private function setFriend(){
        $coupon = Coupon::find(70);
        $customer = Customer::whereEmail("sui201842@outlook.com")->first();
        if($customer&&$coupon)$customer->setFriendShip($coupon);
    }
    function checkdnsrr($hostName, $recType = '') 
    { 
      if(!empty($hostName)) { 
        if( $recType == '' ) $recType = "MX"; 
        exec("nslookup -type=$recType $hostName", $result); 
        // check each line to find the one that starts with the host 
        // name. If it exists then the function succeeded. 
        foreach ($result as $line) { 
          if(eregi("^$hostName",$line)) { 
            return true; 
          } 
        } 
        // otherwise there was no mail handler for the domain 
        return false; 
      } 
      return false; 
    }
    private function makeNewSubscription($customer){
        $plan = SubscriptionPlan::whereServiceId(1)->whereType('Paid')->first();
        $frequency = 6;
        $coupon = null;
        $paymentPlan = PaymentPlan::createOrUpdate($plan, $customer->id, $coupon, $frequency,'nmi');
        $subscription = Subscription::findOrCreate($customer->id,$plan, $coupon,$frequency,'nmi');
        $paymentSubscription = new PaymentSubscription;
        list($now,$paymentSubscription) = $paymentSubscription->createFromPlan($subscription,$paymentPlan);
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
