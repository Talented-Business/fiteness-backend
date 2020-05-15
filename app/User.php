<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;
use App\Weight;
use Mail;
use App\Height;
use App\Mail\NotifyNewCustomer;
use App\Jobs\NotifyNonSubscriber;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    use HasRoles;
    use AdminTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','provider','provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function customer(){
        return $this->hasOne('App\Customer','user_id');
    }
    public function generatePassword(){
        $password = Str::random(8);
        $this->password = Hash::make($password);
        return $password;
    }
    public function extend(){
        $this->active = (int)$this->active;
    }
    
    public static function getUserIP()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }

    public static function createCustomer($record,$provider='email',$providerId=null){
        $serviceId = 1;
        switch($record['application_source']){
            case 'workout':
                $serviceId = 1;
        }
        $ip = self::getUserIP();
        if($ip=='127.0.0.1'){
            $ipInfo = file_get_contents("http://ip-api.com/json/132.45.23.234");
        }else $ipInfo = file_get_contents("http://ip-api.com/json/".$ip);
        $ipInfo = json_decode($ipInfo);
        $timezone = $ipInfo->timezone;
        $country = $ipInfo->country;
        $countryCode = $ipInfo->countryCode;
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $record['first_name'].' '.$record['last_name'],
                'email' => $record['email'],
                'password' => bcrypt($record['password']),
                'provider' => $provider,
                'provider_id' => $providerId
                //'verify_code' => Str::random(32),
            ]);
            $condition = Condition::where('service_id',$serviceId)->skip($record['level']-1)->first();
            $customer = new Customer;
            $customer->first_name = $record['first_name'];
            $customer->last_name = $record['last_name'];
            $customer->gender = ucfirst($record['gender']);
            $customer->birthday = $record['birthday'];
            $customer->register_ip = $ip;
            $customer->country = $country;
            $customer->country_code = $countryCode;
            $customer->timezone = $timezone;
            $customer->user_id = $user->id;
            $customer->email = $record['email'];
            $customer->training_place = $record['place'];
            $customer->initial_height = $record['height'];
            $customer->initial_height_unit = $record['heightUnit'];
            $customer->initial_weight = $record['weight'];
            $customer->initial_weight_unit = $record['weightUnit'];
            $customer->initial_condition = $condition->id;
            $customer->current_height = $record['height'];
            $customer->current_height_unit = $record['heightUnit'];
            $customer->current_weight = $record['weight'];
            $customer->current_weight_unit = $record['weightUnit'];
            $customer->current_condition = $condition->id;
            if(isset($record['couponCode'])){
                $coupon = Coupon::whereCode($record['couponCode'])->first();
                if($coupon){
                    $customer->coupon_id = $coupon->id;
                }
            }
            if($record['place'] == "Casa o Exterior")$customer->weights = "sin pesas";
            else $customer->weights = "con pesas";
            $heightValue = Height::convert($record['height'],$record['heightUnit'])/100;
            $imc = round(Weight::convert($record['weight'],$record['weightUnit'])/$heightValue/$heightValue);
            if(($imc<18.5 && $record['goal']!="strong") || 
                ($imc>=18.5 && $imc<25 && $record['goal']!="fit") || 
                ($imc>=25 && $record['goal']!="cardio")) $customer->objective = $record['goal'];
            $customer->save();
            NotifyNonSubscriber::dispatch($customer, new \App\Mail\NotifyNonSubscriber($customer))->delay(now()->addHours(24));
            $adminEmails = User::adminEmail();//multi
            if(!empty($adminEmails)){
                foreach($adminEmails as $email){
                    //Mail::to($email)->send(new NotifyNewCustomer($customer->first_name,$customer->last_name,$customer->email,$customer->gender));
                }
            }
            $height = new Height;
            $height->customer_id = $customer->id;
            $height->size = Height::convert($record['height'],$record['heightUnit']);
            $height->value = $record['height'];
            $height->unit = $record['heightUnit'];
            $height->save();
            $weight = new Weight;
            $weight->customer_id = $customer->id;
            $weight->size = Weight::convert($record['weight'],$record['weightUnit']);
            $weight->value = $record['weight'];
            $weight->unit = $record['weightUnit'];
            $weight->save();
            $user->customer;
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollback();
            print_r($record);
            // something went wrong
            print_r($e->getMessage());
            return false;
        }        
    }
    public static function generateAcessToken($user){
        $hasWorkoutSubscription = false;
        $hasActiveWorkoutSubscription = false;
        if($user->customer){
            $hasWorkoutSubscription = $user->customer->hasSubscription();
            $subscription = $user->customer->getActiveWorkoutSubscription(); 
            if($subscription){
                $hasActiveWorkoutSubscription = true;
                $s = $subscription->plan->service;
                $s->getMemberships();
                $s['serviceName']=$subscription->plan->service->title;   
                if($subscription->end_date===null){

                }else{
                    $ts1 = strtotime($subscription->end_date);
                    $ts2 = strtotime(date("Y-m-d"));
                    $seconds_diff = $ts1 - $ts2;         
                    $s['expire_at']=floor($seconds_diff/3600/24);   
                    $s['end_date'] = date('d/m/y',strtotime($subscription->end_date));
                }
                $s['expired_date'] = date('d/m/y',strtotime($subscription->nextPaymentTime()));
                $s['start_date'] = date('d/m/y',strtotime($subscription->start_date));
                $s['paid'] = $subscription->plan->type=='Paid';
                $s['status'] = $subscription->status;
                $user->customer['currentWorkoutPlan'] = $subscription->convertFrequency();
                $user->customer['workoutSubscriptionId'] = $subscription->id;
                $user->customer['currentWorkoutPlanPeriod'] = $subscription->currentWorkoutPeriod();
                $user->customer['nextWorkoutPlan'] = $subscription->nextWorkoutPlan();
                $user->customer['renewalOptions'] = $subscription->renewalOptions();
                $user->customer['currentWorkoutMonths'] = $subscription->convertMonths();
            }else{
                $service =Service::find(1);
                $s = ['serviceName'=>$service->title];
                $subscription = Subscription::whereCustomerId($user->customer->id)->where(function($query){
                    $query->whereHas('plan', function ($q) {
                        $q->whereServiceId(1);
                    });            
                })->first();
                if($subscription){
                    if($subscription->end_date)$s['end_date'] = date('d/m/y',strtotime($subscription->end_date));
                    $s['start_date'] = date('d/m/y',strtotime($subscription->start_date));
                    $nextPaymentDate = $subscription->nextPaymentTime();
                    if($nextPaymentDate)$s['expired_date'] = date('d/m/y',strtotime($nextPaymentDate));
                    $s['status'] = $subscription->status;
                }
            }
            $age = \DateTime::createFromFormat('Y-m-d', $user->customer->birthday)
                ->diff(new \DateTime('now'))
                ->y;
            $user->customer['age'] = $age;                
            if($user->customer->country_code)$user->customer->country_code = strtolower($user->customer->country_code);
            $user->customer['services'] = ['1'=>$s];
        }else if($user->type == 'admin'){
            $user['role']=$user->findRole();
            $permissions = $user->getPermissionsViaRoles();
            $result=[];
            foreach($permissions as $permission){
                $result[] = $permission->name;
            }
            $user['permissions'] = $result;
        }
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addMinutes(30);
        $token->save();
        $user['has_workout_subscription']=$hasWorkoutSubscription;
        $user['has_active_workout_subscription']=$hasActiveWorkoutSubscription;
        $defaultCoupon = Coupon::whereCode(Coupon::DEFAULT)->first();
        if($defaultCoupon)$user['defaultCouponId'] = $defaultCoupon->id;
        if($user->customer){
            $heightValue = Height::convert($user->customer->current_height,$user->customer->current_height_unit)/100;
            $user->customer['imc'] = round(Weight::convert($user->customer->current_weight,$user->customer->current_weight_unit)/$heightValue/$heightValue);
            if($user->avatar){
                $user['avatarUrls'] = [
                    'max'=>url("storage/".$user->avatar),
                    'large'=>url("storage/".$user->avatar),
                    'medium'=>url("storage/".$user->avatar),
                    'small'=>url("storage/".$user->avatar),
                ];
            }else{
                if($user->customer->gender=="Male"){
                    $user['avatarUrls'] = [
                        'max'=>url("storage/media/avatar/X-man-large.jpg"),
                        'large'=>url("storage/media/avatar/X-man-large.jpg"),
                        'medium'=>url("storage/media/avatar/X-man-medium.jpg"),
                        'small'=>url("storage/media/avatar/X-man-small.jpg"),
                    ];
                }else{
                    $user['avatarUrls'] = [
                        'max'=>url("storage/media/avatar/X-woman-large.jpg"),
                        'large'=>url("storage/media/avatar/X-woman-large.jpg"),
                        'medium'=>url("storage/media/avatar/X-woman-medium.jpg"),
                        'small'=>url("storage/media/avatar/X-woman-small.jpg"),
                    ];
                }
            }
        }
        return array($user,$tokenResult);
    }
    public static function adminEmail(){
        $emails = [];
        $admins = User::where('type','admin')->get();
        if(isset($admins[0])){
            foreach($admins as $admin){
                $emails[] = $admin->email;
            }
        }
        return $emails;
    }
}
