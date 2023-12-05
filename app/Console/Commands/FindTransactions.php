<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\File;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Cache;
use App\Subscription;
use App\PaymentSubscription;
use App\Transaction;
use App\Customer;
use App\Models\Comment;
use App\Models\Post;
use App\Company;
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
use App\Done;
use App\Models\Media;
use Carbon\Carbon;
use App\Mail\MailQueue;

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
            $customer = Customer::find(3);
            // $customer->changeCoupon(10);
            [$friends, $non] = $customer->getPeople();
            foreach($friends as $friend){
                if(in_array($friend->id, [28, 46, 60, 111])){print_r($friend->id);print_r("@");}
            }
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
                $workout = $customer->getSendableWorkout(-6);
                // print_r($workout['blocks'][0]);
                $text = '';
                foreach($workout['blocks'] as $block){
                    if($block['slug'] === 'comentario'){
                        foreach($block['content'] as $content){
                            if(isset($content['before_content']))$text .= $content['before_content'];
                            if(isset($content['after_content']))$text .= $content['after_content'];
                        }
                    }
                }
                print_r($text);
                var_dump($workout['blog']);
            }
        }
        if(false){
            $subscription = Subscription::find(1021);
            if($subscription){
                $subscription->cancelled_reason = "ok";
                $subscription->save();
            }
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
        if(false){
            $this->updateWorkout();
            $this->updateStaticWorkout();
        }
        if(false){
            $this->bankReminder();
        }
        if(false){
            $this->checkDoneWorkouts();
        }
        if(false){
            $this->findMedal();
        }
        if(false){
            $this->deleteComments();
        }
        if(false){
            $this->checkCarbon();
        }
        if(false){
            $this->checkVideo();
        }
        if(false){
            $this->testingCache();
        }
        if(false){
            $this->generateBirthdayPosts();
        }
        if(false){
            $this->generateArticlePosts();
        }
        if(false){
            $this->likeNotification();
        }
        if(false){
            $this->generateJoinPost();
        }
        if(false){
            $this->generateWorkoutPost();
        }
        if(false){
            $comment = Comment::find(80);
            $customer = Customer::find(15);
            \App\Jobs\CommentOnPost::dispatch($comment, $customer);
        }
        if(false){
            $this->doneTest();
        }
        if(false){
            $this->checkLogging();
        }
        if(false){
            $this->getCustomerIds();
        }
        if(false){
            $this->isFirstTransaction();
        }
        if(false){
            $this->sendFollowingEmail();
        }
        if(false){
            $this->exportCustomers();
        }
        if(true){
            $this->timeCalculate();
        }
    }
    private function timeCalculate(){
        $subscription = Cache::get('1');
        $timeDiff = -14 * 24 + 1;
        if($timeDiff >= -14 * 24 && $timeDiff <= -14 * 24 + 1 && $subscription == null){
            //notification;
            var_dump($timeDiff);
            Cache::put('1', '2', 3600);
        }
    }
    private function exportCustomers(){
        // \App\Jobs\ExportCustomers::dispatch('300', '', 'all');
        $job = new \App\Jobs\ExportCustomers('300', 'sincooh19@gmail.com', 'all');
        $job->handle();
    }
    private function sendFollowingEmail(){
        $customers = Customer::all();
        $i = 0;
        foreach($customers as $customer){
            $follows = DB::table("follows")->select("*")->where('customer_id',$customer->id)->get();
            if($follows->count()>0){
                if(!$customer->hasActiveSubscription()){
                    $userCustomer = Customer::find($follows[0]->follower_id);
                    var_dump($customer->first_name);
                    $data = ['sender_first_name'=>$userCustomer->first_name,'receiver_first_name'=>$customer->first_name,'email'=>$customer->email,'view_file'=>'emails.customers.following','subject'=>$customer->first_name.' y otras personas te empezaron a seguir en Fitemos'];
                    Mail::to($customer->email, $customer->first_name.' '.$customer->last_name)->queue(new MailQueue($data));
                    $i++;
                }    
            }
        }
        var_dump($i);
    }
    private function isFirstTransaction(){
        $customers = Customer::all();
        $ids = [11, 34, 44, 67];
        // foreach($customers as $customer){
        foreach($ids as $id){
            $customer = Customer::find($id);
            $transaction = Transaction::whereCustomerId($customer->id)->first();
            if(Transaction::whereCustomerId($customer->id)->count() === 1){
                echo 'customer';
                var_dump($customer->id);
                $result = $customer->isFirstTransaction($transaction);
                var_dump($result);
            }
        }
    }
    private function getCustomerIds(){
        $transactions = DB::table('transactions')
                ->select('customer_id')
                ->where('status','Declined')
                ->where('id','>','6673')
                ->get();
        foreach($transactions as $transaction){
            print_r($transaction->customer_id);
            print_r(",");
        }
    }
    private function checkLogging(){
        Log::channel('nmiPayments')->info("Info: Start");
        Log::channel('nmiPayments')->error("Info: Start");
        Log::channel('nmiRequests')->info("----Response----\n\n");
        Log::channel('nmiRequests')->error("----Response----\n\n");
    }
    private function doneTest(){
        print_r(config('app.interval_unit'));
        var_dump(env('INTERVAL_UNIT'));
    }
    private function generateWorkoutPost(){
        $customer = Customer::find(3);
        // $startDate = Carbon::createFromFormat('Y-m-d H:i:s', "2021-02-24 19:01:00", 'America/Panama');
        // $endDate = Carbon::createFromFormat('Y-m-d H:i:s', "2021-02-22 19:00:00", 'America/Panama');
        $post = $customer->generateWorkoutPost();
        print_r($post);
    }
    private function generateJoinPost(){
        $activity = new \App\Models\Activity;
        $activity->save();
        $post = new \App\Models\Post;
        $post->fill(['activity_id'=>$activity->id,'customer_id'=>0,'type'=>'join','object_id'=>5882]);
        \App\Models\Post::withoutEvents(function () use ($post) {
            $post->status = 1;
            $post->save();
        });
    }
    private function likeNotification(){
        $activityId = 154;
        $user = User::find(11);
        $post = Post::whereActivityId($activityId)->first();
        if($post && $post->customer_id != $user->customer->id){
            if($post->customer_id>0)\App\Models\Notification::likePost($post->customer_id, $user->customer, $post);
        }
    }
    private function generateArticlePosts(){
        $event = \App\Company::find(22);
        $activity = new \App\Models\Activity;
        $activity->save();
        $post = new Post;
        $post->fill(['activity_id'=>$activity->id,'customer_id'=>0,'type'=>'shop','object_id'=>$event->id]);
        Post::withoutEvents(function () use ($post) {
            $post->status = 1;
            $post->save();
        });
    }
    private function generateBirthdayPosts(){
        $customer = Customer::find(3);
        // $firstDate = "2021-03-05 2:30:00";
        // $firstDate = Carbon::createFromFormat('Y-m-d H:i:s', $firstDate, 'America/Panama');
        // $secondDate = Carbon::createFromFormat('Y-m-d H:i:s', $firstDate, 'America/Panama');
        // $secondDate->add('2','day');
        // $secondDate->add('5','hour');
        $posts = $customer->getNewsfeed(-1, 1);
        // print_r($posts);
    }
    private function testingCache(){
        // Cache::forget('activeCustomerIds');
        if(Cache::has('activeCustomerIds')){
            $value = Cache::get('activeCustomerIds');
            var_dump($value);
        }else{
            // Cache::forever('activeCustomerIds', [1,2,3,4,5,6,7,8,9,10]);    
            Cache::add('activeCustomerIds', [1,2,3,4,5,6,7,8,9,10], 10);
        }
    }
    private function checkVideo(){
        $video = new File(storage_path('app/files/25.qt'));
        print_r($video->getMimeType());
    }
    private function checkCarbon(){
        $doneDate = "2021-03-05 2:30:00";
        $dt = Carbon::createFromFormat('Y-m-d H:i:s', $doneDate, 'America/Panama');
        $dt->sub('1 day');
        $now =  Carbon::now();
        $datatime = iconv('ISO-8859-2', 'UTF-8', strftime("%B %d, %Y ", strtotime($doneDate))).date(" h:i a",strtotime($doneDate));
        if($now->lessThan($dt)){
            print_r($datatime);
            \App\Jobs\EventAttend::dispatch(3,1, $doneDate)->delay($dt);
        }
    }
    private function deleteComments(){
        $comments = Comment::all();
        foreach($comments as $comment){
            $comment->activity->delete();
            $comment->delete();
        }
    }
    private function findMedal(){
        $customer = Customer::find(3);
        $object = $customer->findMedal();
        print_r($object);
    }
    private function checkDoneWorkouts(){
        $customers = Customer::whereIn('id',[3])->get();
        foreach($customers as $customer){
            $begin = new \DateTime('2020-05-11');
            $end = new \DateTime('2021-01-17');

            $interval = \DateInterval::createFromDateString('1 day');
            $period = new \DatePeriod($begin, $interval, $end);
            $dones = Done::whereCustomerId($customer->id)->get();
            if($dones->count()>0){
                foreach($dones as $index=>$done){
                    if($done->id == 14822){
                        print_r($customer->id."-");
                        print_r("\n");
                    }
                    if($done->done_date == '0000-00-00'){
                        print_r($customer->id."-");
                        print_r("\n");
                        $done->delete();
                    }
                }
                foreach ($period as $dt) {
                    $date = $dt->format("Y-m-d");
                    $dones = Done::whereCustomerId($customer->id)->whereDoneDate($date)->get();
                    if($dones->count()>1){
                        print_r($customer->id."-".$date);
                        print_r("\n");
                        foreach($dones as $index=>$done){
                            if($done->done_date == '0000-00-00'){
                                $done->delete();
                            }
                            if($index>0){
                                $done->delete();
                                // print_r($index);
                            }
                        }
                        print_r("\n");
                    }
                }                
            }
        }
    }
    private function bankReminder(){
        $request = BankTransferRequest::find(7);
        $subscription = Subscription::whereCustomerId($request->customer_id)->wherePlanId($request->plan_id)->first();
        if($subscription){
            Bank::scraping($subscription);
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
