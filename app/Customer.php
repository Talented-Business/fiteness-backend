<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Twilio\Rest\Client;
//use App\Jobs\SendEmail;
use App\Exports\CustomersExport;
use App\Payment\Bank;
use Mail;

class Customer extends Model
{
    protected $fillable = ['username','first_name','last_name','gender','birthday','whatsapp_phone_number','policy','status','country','country_code'];    
    private $pageSize;
    private $statuses;
    private $pageNumber;
    private $search;
    private $status;
    private $activeWorkoutSubscription;
    public static function validateRules(){
        return array(
            'first_name'=>'required|max:255',
            'last_name'=>'required|max:255',
            'birthday'=>'required|max:255',
            'gender'=>'required',
            'whatsapp_phone_number'=>'required|max:255',
        );
    }
    public static function validateUserSettingRules($user_id,$customer_id){
        return array(
            //'email'=>'required|max:255|unique:users,email,'.$user_id,
            //'customer_email'=>'required|max:255|unique:customers,email,'.$customer_id,
            'username'=>'required|max:255|unique:companies,username|unique:customers,username,'.$customer_id.'|not_in:'.Company::NOT_IN,
            'first_name'=>['required','max:255'],
            'last_name'=>['required','max:255'],
            'current_height'=>['required'],
            'gender'=>['required'],
            'whatsapp_phone_number'=>'max:255',
        );
    }
    public static function validateMailSettingRules($user_id,$customer_id){
        return array(
            'email'=>'required|max:255|unique:users,email,'.$user_id,
            'email'=>'required|max:255|unique:customers,email,'.$customer_id,
            'active_email'=>['required'],
            'active_whatsapp'=>['required'],
        );
    }
    private static $searchableColumns = ['search','status'];
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    public function record(){
        return $this->hasOne('App\Record','customer_id');
    }
    public function coupon(){
        return $this->belongsTo('App\Coupon','coupon_id');
    }
    public function done(){
        return $this->hasMany('App\Done');
    }
    public function doneWorkouts(){
        return $this->done()->whereType('workout');
    }
    public function subscriptions(){
        return $this->hasMany('App\Subscription');
    }
    public function transactions(){
        return $this->hasMany('App\Transaction');
    }
    public function invoices(){
        return $this->hasMany('App\Invoice');
    }
    public function latestWeights(){
        return $this->hasMany('App\Weight')->orderBy('created_at','desc')->limit(5);
    }
    public function followings(){
        return $this->belongsToMany('App\Customer','follows','follower_id','customer_id')->wherePivot('status', 'accepted');
    }
    public function followers(){
        return $this->belongsToMany('App\Customer','follows','customer_id','follower_id')->wherePivot('status', 'accepted');
    }    
    public function mutedRelations(){
        return $this->belongsToMany('App\Customer','customers_relations','customer_id','follower_id')->withPivot('status');
    }
    public function blockedRelations(){
        return $this->belongsToMany('App\Customer','customers_relations','follower_id','customer_id')->wherePivot('status','blocked');
    }
    public function readingPosts(){
        return $this->belongsToMany('App\Model\Post','reading_posts','customer_id','post_id')->wherePivot('status','completed');
    }
    public function assign($request){
        foreach($this->fillable as $property){
            if($request->exists($property)){
                $this->{$property} = $request->input($property);
            }
        }
    }
    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.$this->last_name;
    }    
    public static function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }    
    /**
     * Get the customers's social mute status
     *
     * @return boolean
     */
    public function getMuteStatusAttribute()
    {
        $action = \App\Models\AdminAction::whereObjectId($this->id)->whereObjectType('customer')->whereIn('type',['mute','unmute'])->orderBy('id','desc')->first();
        if($action === null)return false;
        if($action->type === 'unmute')return false;
        $now = Carbon::now();
        if($action->content['days']){
            $now->subDays($action->content['days']);
        }
        return $now->lessThan($action->created_at);
    }
    public function extends(){
        $this['age'] = \DateTime::createFromFormat('Y-m-d', $this->birthday)->diff(new \DateTime('now'))->y;
        $dates = explode (' ',$this->created_at);
        $this['registration_date'] = $dates[0];
        $this->coupon;
        $this->subscriptions;
        $this['imc'] = $this->getImc();
        $this['initial_imc'] = $this->initialImc();
        $total = 0;
        $this['serviceName'] = '';
        $this['planFrequency'] = '';
        $this['activeSubscription'] = 0;
        foreach($this->subscriptions as $subscription){
            $subscription->extends();
            if($subscription->status == 'Active')$this['activeSubscription'] = 1;
            $total += $subscription['total_paid'];
            $this['serviceName'] .= $subscription->plan->service->title.' ';
            $this['planFrequency'] .= $subscription->frequency.' ';
        }
        $this['total_paid'] = $total;
        if($this->user->active == 0){
            $this['status'] = "Disabled";
        }else{
            $status = "Inactive";
            foreach($this->subscriptions as $subscription){
                if($subscription->status == "Active"){
                    $status = "Active";
                    if($subscription->end_date)$status = "Leaving";
                }else if($subscription->status == "Cancelled"){
                    $status = "Cancelled";
                }
            }
            $this['status'] = $status;
        }
        $this['muteStatus'] = $this->muteStatus;
    }
    public function search(){
        $where = Customer::whereRaw('1');
        if($this->search!=null){
            $where->where('first_name','like','%'.$this->search.'%');
            $where->orWhere('last_name','like','%'.$this->search.'%');
            $where->orWhere('username','like','%'.$this->search.'%');
            $where->orWhere('email','like','%'.$this->search.'%');
            $where->orWhere('country','like','%'.$this->search.'%');
        }
        switch($this->status){
            case "Active":
                $where->where(function($query){
                    $query->whereHas('user', function($q){
                        $q->where('active','=','1');
                    });
                    $query->whereHas('subscriptions', function($q){
                        $q->where('status','=',"Active")->whereNull('end_date');
                    });
                });
                break;
            case "Cancelled":
                $where->where(function($query){
                    $query->whereHas('user', function($q){
                        $q->where('active','=','1');
                    });
                    $query->whereHas('subscriptions', function($q){
                        $q->where('status','=',"Cancelled");
                    });
                });
                break;
            case "Leaving":
                $where->where(function($query){
                    $query->whereHas('user', function($q){
                        $q->where('active','=','1');
                    });
                    $query->whereHas('subscriptions', function($q){
                        $q->where('status','=',"Active")->whereNotNull('end_date');
                    });
                });
                break;
            case "Inactive":
                $where->where(function($query){
                    $query->whereHas('subscriptions', function($q){
                        $q->where('status','!=',"Active");
                    });
                    $query->orDoesntHave('subscriptions');
                    $query->whereHas('user', function($q){
                        $q->where('active','=','1');
                    });
                });
                break;
            case "Disabled":
                $where->where(function($query){
                    $query->whereHas('user', function($q){
                        $q->where('active','=','0');
                    });
                });
            break;
        }
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('id', 'ASC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $index=> $customer){
            $age = \DateTime::createFromFormat('Y-m-d', $customer->birthday)
                ->diff(new \DateTime('now'))
                ->y;
            $items[$index]['age'] = $age;
            $items[$index]['trial'] = 0;
            $items[$index]['imc'] = $customer->getImc();
            $items[$index]['initial_imc'] = $customer->initialImc();
            if($customer->user->active == 0){
                $items[$index]['status'] = "Disabled";
            }else{
                $status = "Inactive";
                foreach($customer->subscriptions as $subscription){
                    if($subscription->status == "Active"){
                        $status = "Active";
                        if($subscription->end_date)$status = "Leaving";
                        if($subscription->plan_id == 1) $items[$index]['trial'] = 1;
                    }else if($subscription->status == "Cancelled"){
                        $status = "Cancelled";
                    }
                }
                $items[$index]['status'] = $status;
            }
            if($customer->country == null){
                $c = Customer::find($customer->id);
                //= self::ip_info($c->register_ip, "Country");
                $xml = simplexml_load_file("http://ip-api.com/xml/".$c->register_ip);
                $c->country = $xml->country;        
                $c->save();
                //$items[$index]['country'] = $xml->country;
            }else{
            }
        }        
        return $response;
    }
    public function searchAll(){
        $where = Customer::whereRaw('1');
        if($this->search!=null){
            $where->where('first_name','like','%'.$this->search.'%');
            $where->orWhere('last_name','like','%'.$this->search.'%');
            $where->orWhere('email','like','%'.$this->search.'%');
            $where->orWhere('country','like','%'.$this->search.'%');
        }
        switch($this->status){
            case "Active":
                $where->where(function($query){
                    $query->whereHas('user', function($q){
                        $q->where('active','=','1');
                    });
                    $query->whereHas('subscriptions', function($q){
                        $q->where('status','=',"Active")->whereNull('end_date');
                    });
                });
                break;
            case "Cancelled":
                $where->where(function($query){
                    $query->whereHas('user', function($q){
                        $q->where('active','=','1');
                    });
                    $query->whereHas('subscriptions', function($q){
                        $q->where('status','=',"Cancelled");
                    });
                });
                break;
            case "Leaving":
                $where->where(function($query){
                    $query->whereHas('user', function($q){
                        $q->where('active','=','1');
                    });
                    $query->whereHas('subscriptions', function($q){
                        $q->where('status','=',"Active")->whereNotNull('end_date');
                    });
                });
                break;
            case "Inactive":
                $where->where(function($query){
                    $query->whereHas('user', function($q){
                        $q->where('active','=','1');
                    });
                    $query->whereHas('subscriptions', function($q){
                        $q->where('end_date','<',date("Y-m-d"))->orWhere('status','!=',"Active");
                    });
                });
                break;
            case "Disabled":
                $where->where(function($query){
                    $query->whereHas('user', function($q){
                        $q->where('active','=','0');
                    });
                });
            break;
        }
        return $where->get();
    }
    public function assignSearch($request){
        foreach(self::$searchableColumns as $property){
            $this->{$property} = $request->input($property);
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public function assignExport($search, $status){
        $this->status = $status;
        $this->search = $search;
    }
    public function hasSubscription(){//workoutSubscription
        /*$subscription = Subscription::where('frequency','Once')->where('customer_id','=',$this->id)->where(function($query){
            $query->whereHas('plan', function($q){
                $q->where('type','=','Free');
                $q->where('service_id','=','1');
            });
        })->first();*/
        $subscription = Subscription::where('customer_id','=',$this->id)->where(function($query){
            $query->whereHas('plan', function($q){
                $q->where('service_id','=','1');
            });
        })->first();
        if($subscription && ($subscription->payment_plan_id || $subscription->status == 'Cancelled')) return true;
        return false;
    }
    public function hasActiveSubscription(){
        $activeSubscription = Subscription::where('customer_id','=',$this->id)->where(function($query){
            $today = date('Y-m-d H:i:s');
            $query->where('status','Active')->where('start_date','<=',$today);
            $query->Orwhere('end_date','>=',$today);
            $query->whereHas('plan', function($q){
                $q->where('service_id','=','1');
            });
        })->first();
        if($activeSubscription) {
            $this->activeWorkoutSubscription = $activeSubscription;
            return true;
        }
        return false;
    }
    public function getActiveWorkoutSubscription(){
        $activeSubscription = Subscription::where('customer_id','=',$this->id)->where(function($query){
            $today = date('Y-m-d H:i:s');
            $query->where('status','Active')->where('start_date','<=',$today);
            $query->orWhere(function($query){
                $today = date('Y-m-d H:i:s');
                $query->where('end_date','>=',$today);
                $query->where('status','Active');
            });
        })->where(function($query){
            $query->whereHas('plan', function($q){
                $q->where('service_id','=','1');
            });
        })
        ->first();
        return $activeSubscription;
    }
    public function getImc(){
        $heightValue = Height::convert($this->current_height,$this->current_height_unit)/100;
        $imc = round(Weight::convert($this->current_weight,$this->current_weight_unit)/$heightValue/$heightValue);
        return $imc;
    }
    private function initialImc(){
        $heightValue = Height::convert($this->initial_height,$this->initial_height_unit)/100;
        $imc = round(Weight::convert($this->initial_weight,$this->initial_weight_unit)/$heightValue/$heightValue);
        return $imc;
    }
    private function findWorkoutFilters($date){
        $level = $this->current_condition;
        $weightsCondition = $this->weights;
        $objective = $this->objective;
        $gender = $this->gender;
        $imc = $this->getImc();
        if($objective=="auto"){
            if($imc<18.5){
                $objective = "strong";
            }else if($imc>=25){
                $objective = "cardio";
            }else{
                $objective = "fit";
            }
        }
        $workoutCondition = Condition::find($level);
        return [$workoutCondition,$weightsCondition,$objective,$gender];
    }
    private function findWorkoutCurrentDate(){
        $userTimezone = new \DateTimeZone($this->timezone);
        $objDateTime = new \DateTime('NOW');
        $objDateTime->setTimezone($userTimezone);
        $fiveHours = \DateInterval::createFromDateString('+5 hours');
        // $objDateTime->add($fiveHours);
        return $objDateTime->format('Y-m-d');
    }
    private function setActiveWorkoutSubscription(){
        $subscription = Subscription::whereCustomerId($this->id)->where(function($query){
            $query->whereHas('plan', function($q){
                $q->where('service_id','=','1');
            });
        })->first();
        $this->activeWorkoutSubscription = $subscription;
    }
    private function findSendableWorkout($today){
        list($workoutCondition,$weightsCondition,$objective,$gender) = $this->findWorkoutFilters($today);
        if($this->activeWorkoutSubscription->isNewWeek($today)){
            $weekdate = date('w',strtotime($today))-1;
            if($weekdate<0)$weekdate+=7;
            if($weekdate>6)$weekdate-=7;
            $fromDate = $this->activeWorkoutSubscription->getFirstWorkoutStartDate();
            if(date('Y-m-d',strtotime($fromDate))>$today){
                $workout = null;
            }else{
                $fromDate = date('w',strtotime($fromDate))-1;
                if($fromDate<0)$fromDate+=7;
                if($fromDate>6)$fromDate-=7;
                $workout = StaticWorkout::sendable($fromDate,$weekdate,$today,$workoutCondition,$weightsCondition,$objective,$gender,$this->id);
            }
        }else{
            $workout = Workout::sendable($today,$workoutCondition,$weightsCondition,$objective,$gender,$this->id);
        }
        return $workout;
    }
    public function findPartnerDiscount(){
        $partners = Customer::whereFriendId($this->id)->get();
        $hasPartner = false;
        foreach($partners as $partner){
            if($partner->hasActiveSubscription()){
                $hasPartner = true;
            }
        }
        if($hasPartner){
            return Setting::getReferralDiscount();
        }
        return false;
    }
    public function changeLevel(){
        $level = LevelTest::whereCustomerId($this->id)->orderBy('created_at','desc')->first();
        if($level){
            $current = $level->repetition;
        }else{
            $current = 0;
        }
        if($current<20){
            $this->current_condition = 1;
        }else if($current<40){
            $this->current_condition = 2;
        }else if($current<60){
            $this->current_condition = 3;
        }else if($current<80){
            $this->current_condition = 4;
        }else{
            $this->current_condition = 5;
        }
        $this->save();
    }
    public function findWorkouts($date=null){
        $workouts = ['current'=>null,'previous'=>null,'next'=>null];
        if($this->hasActiveSubscription()){
            if($this->activeWorkoutSubscription==null){
                $this->setActiveWorkoutSubscription();
            }
            if($date == null){
                $today = $this->findWorkoutCurrentDate();
            }else{
                $today = $date;
            }
            $i = 0;
            setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
            //$today = date('Y-m-d', strtotime('+1 days', strtotime($today)));
            while( $workouts['current'] == null){
                $workout = $this->findSendableWorkout($today);
                if($workout){
                    $workouts['current'] = ['date'=>$workout['date'],'short_date'=>$workout['short_date'],'blocks'=>$workout['blocks'],'content'=>$workout['content'],'blog'=>$workout['blog'],'read'=>$this->readDone($today),'today'=>$today];
                    $previousDate = date('Y-m-d', strtotime('-1 days', strtotime($today)));
                    $nextDate = date('Y-m-d', strtotime('+1 days', strtotime($today)));
                }else{
                    $today = date('Y-m-d', strtotime('-1 days', strtotime($today)));
                    $i++;
                    if($i>100){break;}
                }
            }
            $currentDate = $this->currentDate();
            if(isset($previousDate)){
                $today = $previousDate;
                while( $workouts['previous'] == null){
                    if(strtotime($today) + 3600*24*2<strtotime($currentDate)){
                        //break;
                    }
                    $workout = $this->findSendableWorkout($today);
                    if($workout){
                        $workouts['previous'] = ['date'=>$workout['date'],'blocks'=>$workout['blocks'],'content'=>$workout['content'],'blog'=>$workout['blog'],'read'=>$this->readDone($today),'today'=>$today];
                    }else{
                        $today = date('Y-m-d', strtotime('-1 days', strtotime($today)));
                        $i++;
                        if($i>100)break;
                    }
                }
            }
            if(isset($nextDate)){
                $today = $nextDate;
                while( $workouts['next'] == null){
                    if(strtotime($today)>strtotime($currentDate)){
                        //break;
                    }
                    $workout = $this->findSendableWorkout($today);
                    if($workout){
                        $workouts['next'] = ['date'=>$workout['date'],'blocks'=>$workout['blocks'],'content'=>$workout['content'],'blog'=>$workout['blog'],'read'=>$this->readDone($today),'today'=>$today];
                    }else{
                        $today = date('Y-m-d', strtotime('+1 days', strtotime($today)));
                        $i++;
                        if($i>100)break;
                    }
                }
            }
        }
        return $workouts;
    }
    private function replace($content){
        $content = str_ireplace("{h1}","<h1>",$content);
        $content = str_ireplace("{/h1}","</h1>",$content);
        $content = str_ireplace("{h2}","<h2>",$content);
        $content = str_ireplace("{/h2}","</h2>",$content);
        $shortcodes = Shortcode::where('status','=','Active')->get();
        foreach($shortcodes as $shortcode){
            $content = str_replace("{{$shortcode->name}}","<a href='$shortcode->link' >$shortcode->name</a>",$content);
        }
        return $content;
    }
    public function setFriendShip($coupon){
        if( $coupon && $coupon->type=="Referral"){
            $friend = Customer::find($coupon->customer_id);
            if( $friend->hasActiveSubscription() ){
                $this->friend_id = $coupon->customer_id;
                $this->friend = "yes";
                $this->save();
                \App\Models\Notification::referralJoin($coupon->customer_id, $this);
            }
        }
    }
    public function removeFriendShip(){
        if($this->friend_id)\App\Models\Notification::referralLeave($this->friend_id, $this);
        $this->friend_id = null;
        $this->friend = "no";
        $this->save();
    }
    public function recentWorkouts(){
        $workouts = [];
        if($this->hasActiveSubscription()){
            if($this->activeWorkoutSubscription==null){
                $this->setActiveWorkoutSubscription();
            }
            $today = $this->findWorkoutCurrentDate();
            $workouts = [];
            $i = 0;
            setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
            while(count($workouts)<3){
                $workout = $this->findSendableWorkout($today);
                if($workout){
                    $sentences = explode("\n",$workout['content'][0]);
                    foreach($sentences as $index=>$sentence){
                        if(trim($sentences[$index])!=="")$sentences[$index] = "<p>".$sentences[$index]."</p>";
                    }
                    $workout['content'][0] = implode("\n",$sentences);
                    $workouts[] = ['date'=>$workout['date'],'content'=>$workout['content'],'blog'=>$workout['blog'],'read'=>$this->readDone($today),'today'=>$today];
                }
                $today = date('Y-m-d', strtotime('-1 days', strtotime($today)));
                $i++;
                if($i>100)break;
            }
        }
        return $workouts;
    }
    private function readDone($date){
        $done = Done::whereCustomerId($this->id)->whereDoneDate($date)->first();
        if($done) return true;
        else return false;
    }
    public function getSendableWorkout($diff){
        $workout=null;
        if($this->hasActiveSubscription()){
            if($this->activeWorkoutSubscription==null){
                $subscription = Subscription::whereCustomerId($this->id)->where(function($query){
                    $query->whereHas('plan', function($q){
                        $q->where('service_id','=','1');
                    });
                })->first();
                $this->activeWorkoutSubscription = $subscription;
            }
            if($this->activeWorkoutSubscription){
                $userTimezone = new \DateTimeZone($this->timezone);
                $objDateTime = new \DateTime('NOW');
                $objDateTime->setTimezone($userTimezone);
                $today = $objDateTime->format('Y-m-d');
                if($diff>0)$today = date('Y-m-d', strtotime('+1 days', strtotime($today)));
                if($diff<0)$today = date('Y-m-d', strtotime('-1 days', strtotime($today)));
                setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
                list($workoutCondition,$weightsCondition,$objective,$gender) = $this->findWorkoutFilters($today);
                if($this->activeWorkoutSubscription->isNewWeek($today)){
                    $weekdate = date('w',strtotime($today))-1;
                    if($weekdate<0)$weekdate+=7;
                    if($weekdate>6)$weekdate-=7;
                    $fromDate = $this->activeWorkoutSubscription->getFirstWorkoutStartDate();
                    $fromDate = date('w',strtotime($fromDate))-1;
                    if($fromDate<0)$fromDate+=7;
                    if($fromDate>6)$fromDate-=7;
                    $workout = StaticWorkout::sendable($fromDate,$weekdate,$today,$workoutCondition,$weightsCondition,$objective,$gender,$this->id);
                }else{
                    $workout = Workout::sendable($today,$workoutCondition,$weightsCondition,$objective,$gender,$this->id);
                }
            }
        }
        return $workout;
    }
    public function findReferralCoupon(){
        $referralCoupon = Coupon::whereType('Referral')->whereCustomerId($this->id)->whereStatus('Active')->first();
        if( $referralCoupon && $this->getActiveWorkoutSubscription() )return $referralCoupon;
        return null;
    }
    public function sendFirstWorkout(){
        $userTimezone = new \DateTimeZone($this->timezone);
        $objDateTime = new \DateTime('NOW');
        $objDateTime->setTimezone($userTimezone);
        $hour = $objDateTime->format('H');
        $weekDay = $objDateTime->format('l');
        if( $hour >= 19){
            /*if($weekDay=='Wednesday'){
                $workout = $this->getSendableWorkout(0);
            }*/
            $workout = $this->getSendableWorkout(0);
            if($workout){
                $this->send($workout);
            }
            $workout = $this->getSendableWorkout(1);
        }else{
            //if($weekDay=='Thursday')$workout = $this->getSendableWorkout(-1);
            $workout = $this->getSendableWorkout(0);
        }
        if($workout){
            $this->send($workout);
        }
        $this->createJoinPost();
    }
    private function createJoinPost(){
        $activity = new \App\Models\Activity;
        $activity->save();
        $post = new \App\Models\Post;
        $post->fill(['activity_id'=>$activity->id,'customer_id'=>0,'type'=>'join','object_id'=>$this->id]);
        \App\Models\Post::withoutEvents(function () use ($post) {
            $post->status = 1;
            $post->save();
        });
    }
    public function send($workout){
        if($this->active_email){
            dispatch(function () use ($workout)  {
                $config = new Config;
                $construct = $config->findByName('sendmail handle'.$this->id);
                $config->updateConfig('sendmail handle'.$this->id, date("Y-m-d H:i:s"));
            });
            //SendEmail::dispatch($this,new \App\Mail\Workout($workout['date'],$workout['content'],$workout['blog']));
            Mail::to($this->email)->send(new \App\Mail\Workout($workout['date'],$workout['content'],$workout['blog']));
        }
        if($this->active_whatsapp && $this->whatsapp_phone_number&&getenv("APP_ENV")!="local"){
            $to = $this->whatsapp_phone_number;
            $from = config('services.twilio.whatsapp_from');
            $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
            $status =  $twilio->messages->create('whatsapp:' . $to, [
                "from" => 'whatsapp:' . $from,
                "body" => $workout['whatsapp'][0]
            ]);
        }
    }
    public static function verifyEmail($to){
        $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));

        $verification_check = $twilio->verify->v2->services("VA048c75d69c9c7dfcc6a2d24c06a3bf8e")
                                                 ->verificationChecks
                                                 ->create("123456", // code
                                                          ["to" => $to]
                                                 );
        return $verification_check->sid; 
    }
    public function changeCoupon($couponId){
        if($this->coupon_id  != $couponId){
            $coupon = Coupon::find($couponId);
            if($coupon->type=='Public'){
                $this->coupon_id  = $couponId;
                $this->save();
            }
        }
    }
    public function findRecord(){
        if($this->record == null){
            $this->record = new Record;
            $this->record->customer_id = $this->id;
            $this->record->benckmark_count = 0;
            $this->record->video_count = 0;
            $this->record->email_count = 0;
            $this->record->blog_count = 0;
            $this->record->session_count = 0;
            $this->record->edit_count = 0;
            $this->record->contact_count = 0;
        }
        return $this->record;
    }
    public function increaseRecord($colmun){
        $record = $this->findRecord();
        if(isset($record[$colmun])&&$record[$colmun]>0){
            $record[$colmun] = $record[$colmun] + 1;
        }else{
            $record[$colmun] = 1;
        }
        $record->save();
    }
    public function isFirstPayment($serviceId){
        $subscription = Subscription::whereCustomerId($this->id)->first();
        if($subscription){
            return !Bank::isOld($subscription);
        }
        return true;
    }
    public function findMedal(){
        $doneworkouts = ActivityWorkout::whereCustomerId($this->id)->whereType('complete')->get();
        $workoutCount = $doneworkouts->count();
        $fromWorkout = 0;
        $fromWorkoutImage = null;
        $toWorkout = 0;
        $toWorkoutImage = null;
        $totalWorkoutLevels = Medal::whereType('total')->orderBy('count')->get();
        foreach($totalWorkoutLevels as $index=>$level){
            $toWorkout = $level->count;
            $toWorkoutImage = secure_url('storage/'.$level->image);
            if($workoutCount>$level->count){
            }else{
                if(isset($totalWorkoutLevels[$index-1])){
                    $fromWorkout = $totalWorkoutLevels[$index-1]['count'];
                    $fromWorkoutImage = secure_url('storage/'.$totalWorkoutLevels[$index-1]['image']);
                }
                break;
            }
        }
        //level
        $levelMedals = Medal::whereType('level')->orderBy('count')->get();
        foreach($levelMedals as $index=>$level){
            if($this->current_condition == $level->count){
                $levelMedalImage = secure_url('storage/'.$level->image);
                break;
            }
        }
        //month
        $monthDoneworkouts = collect();
        $doneworkouts->each(function($doneworkout) use ($monthDoneworkouts) {
            if( substr($doneworkout->done_date,0,7) == substr(date("Y-m-d"),0,7)){
                $monthDoneworkouts->push($doneworkout);
            }
        });
        $fromMonthWorkout = 0;
        $fromMonthWorkoutImage = null;
        $toMonthWorkout = 0;
        $toMonthWorkoutImage = null;
        $monthWorkoutCount = count($monthDoneworkouts);
        $monthWorkoutTotal = date("d");
        $monthPercent = round($monthWorkoutCount/$monthWorkoutTotal*100);
        $monthWorkoutLevels = Medal::whereType('month')->orderBy('count')->get();
        foreach($monthWorkoutLevels as $index=>$level){
            $toMonthWorkout = $level->count;
            $toMonthWorkoutImage = secure_url('storage/'.$level->image);
            if($monthPercent>$level->count){
            }else{
                if(isset($monthWorkoutLevels[$index-1])){
                    $fromMonthWorkout = $monthWorkoutLevels[$index-1]['count'];
                    $fromMonthWorkoutImage = secure_url('storage/'.$monthWorkoutLevels[$index-1]['image']);
                }
                break;
            }
        }
        return ['fromWorkout'=>$fromWorkout,
            'fromWorkoutImage'=>$fromWorkoutImage,
            'toWorkout'=>$toWorkout,
            'toWorkoutImage'=>$toWorkoutImage,
            'workoutCount'=>$workoutCount,
            'levelMedalImage'=>isset($levelMedalImage)?$levelMedalImage:null,
            'fromMonthWorkout'=>$fromMonthWorkout,
            'fromMonthWorkoutImage'=>$fromMonthWorkoutImage,
            'toMonthWorkout'=>$toMonthWorkout,
            'toMonthWorkoutImage'=>$toMonthWorkoutImage,
            'monthWorkoutCount'=>$monthWorkoutCount,            
            'monthWorkoutTotal'=>$monthWorkoutTotal,
            'monthShortName'=>ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%h", time()))),
            'monthPercent'=>$monthPercent,
        ];
    }
    public function removePaymentMethods(){
        $tokens = PaymentTocken::whereCustomerId($this->id)->get();
        foreach($tokens as $token){
            $nmiClient = new NmiClient;
            $nmiClient->deleteCustomerVault($token->tocken,$this->id);
            $token->delete();
        }
        $this->nmi_vault_id = null;
        $this->save();
    }
    private static function calculateMonthDiff($firstDate,$secondDate){
        $d1 = new \DateTime($firstDate);
        $d2 = new \DateTime($secondDate);
        $interval = $d2->diff($d1);
        $year = $interval->format('%y');
        $month = $interval->format('%m');
        $day = $interval->format('%d');
        return $year*12 + $month + round($day/30,2);
    }
    public function findReferralUrl(){
        $code = "r".$this->id;
        $coupon = Coupon::whereCode($code)->first();
        if($coupon == null){
            $coupon = new Coupon;
            $coupon->type = "Referral";
            $coupon->name = Coupon::REFERRAL_NAME;
            $coupon->code = $code;
            $coupon->customer_id = $this->id;
            $coupon->discount = Setting::getReferralDiscount();
            $coupon->renewal = 1;
            $coupon->save();
        }
        return config('app.url').Coupon::REFERRAL_URL.$code;
    }
    public function findPartners(){
        $partners = Customer::whereFriendId($this->id)->get();
        $list = [];
        foreach($partners as $partner){
            if($partner->hasActiveSubscription()){
                if($partner->user->avatar){
                    $data = pathinfo($partner->user->avatar);
                    $avatarFile = $data['dirname']."/avatar/".$data['filename'].".".$data['extension'];        
                    $partner['avatarUrls'] = [
                        'max'=>secure_url("storage/".$partner->user->avatar),
                        'large'=>secure_url("storage/".$partner->user->avatar),
                        'medium'=>secure_url("storage/".$partner->user->avatar),
                        'small'=>secure_url("storage/".$avatarFile),
                    ];
                }else{
                    if($partner->gender=="Male"){
                        $partner['avatarUrls'] = [
                            'max'=>secure_url("storage/media/avatar/X-man-large.jpg"),
                            'large'=>secure_url("storage/media/avatar/X-man-large.jpg"),
                            'medium'=>secure_url("storage/media/avatar/X-man-medium.jpg"),
                            'small'=>secure_url("storage/media/avatar/X-man-small.jpg"),
                        ];
                    }else{
                        $partner['avatarUrls'] = [
                            'max'=>secure_url("storage/media/avatar/X-woman-large.jpg"),
                            'large'=>secure_url("storage/media/avatar/X-woman-large.jpg"),
                            'medium'=>secure_url("storage/media/avatar/X-woman-medium.jpg"),
                            'small'=>secure_url("storage/media/avatar/X-woman-small.jpg"),
                        ];
                    }
                }
                $list[] = $partner;
            }
        }
        return $list;
    }
    public function findCurrentSurvey(){
        $activeSurveys = Survey::active();
        foreach($activeSurveys as $survey){
            $items = $survey->items;
            $surveyExists = false;
            foreach($items as $item){
                $report = SurveyReport::whereCustomerId($this->id)->whereSurveyItemId($item->id)->first();
                if($report){
                    $surveyExists = true;
                    continue;
                }
            }
            if(!$surveyExists){
                foreach($survey->items as $item){
                    $item->options;
                }
                return $survey;
            }
        }
        return null;
    }
    public static function export($customers){
        $tenPercentCoupons = Coupon::whereType('Private')->whereDiscount('10')->whereForm('%')->get();
        $tenPercentCouponsIds = [];
        foreach($tenPercentCoupons as $tenPercentCoupon){
            $tenPercentCouponsIds[] = $tenPercentCoupon->id;
        }
        $itemsArray = [];
        $itemsArray[] = ['ID','Nombre','Last Name','Correo',
        'Cliente (SI / NO )',// SI → if customer pay at least one cent,No → if customer has not paid
        'Quiere verlo en el correo',//new active_email
        'Whatsapp',
        'Quiere recibir WA',//new active_whatsapp
        'Código de entrada',
        'PLAN DE SUSCRIPCIÓN',//new subscription frequency
        'Cliente Pago',// new payment have?Si/No
        'estado',//new status
        'TARJETA PEGADA',//new payment card registered?Si/No
        'TIPO DE PAGO',//new payment card type
        'RECIBIENDO SERVICIO',//active subscription?Si/No
        'FECHA REGISTRO',//Registration Date
        'Inicio de suscripción',//date when subscription start
        'FECHA PRIMER PAGO',//First Payment Date
        'MESES ENTRE PAGO Y REGISTRO',// new the count of months between registeration date and first payment date.
        'FECHA CANCELACIÓN SUSCRIPCIÓN',//new cancellation date
        //'Cancellation',//cancellation reason
        'Stars',
        'Motivo',
        'Feedback',
        'Tiempo Activo En Suscripción Meses',//new the count of months of current active subscription
        'Cantidad de Renovaciones',//new the count of renewaling
        'MESES PAGADOS',//new the count of months paid
        'MESES SERVICIO RECIBIDO',//new the count of months of using service
        'MESES PARA RENOVACIÓN',//new remaining the count of months until next payment
        'VENTAS ACUMULADAS',//new total money
        'DESC SALIDA',//new 10% lifetime discount accept or no accept
        'Sexo',//gender 
        'Condición física',/*Condición Física Inicial*/'Condición Física Actual','Diferencia Nivel Físico',
        'Lugar de Entrenamiento',//training_place
        'Altura',//Height
        'Peso Inicial','Peso Actual','Diferencia de Peso',
        'IMC inicial','IMC actual','Diferencia IMC','Workouts Ingrsados','Objetivo',
        'Edad','País',// country
        'Click on videos','Click on email links','Click on Blogs',
        'Tiempo activo en website','Actualizaciones totales dentro del perfil','contact request',
        '¿Cómo nos conociste?'
        ];
        $total = 0;
        foreach($customers as $index=>$customer){
            $customer->extends();
            $status = '';
            $frequencyString = '-';
            $cancellationDate = null;
            $currentActiveSubscriptionProgress = '';
            $renewalCount = 0;
            $cancelledNow = 'no';
            $stars = '';
            $reason = '';
            $feedback = '';
            $subscriptionStartDate = '';
            $cardTypes = [];
            if($customer->user->active == 0){
                $status = "Disabled";
            }else{
                $status = "Inactive";
                foreach($customer->subscriptions as $subscription){
                    $frequencyString = $subscription->frequency;
                    if($subscription->status == "Active"){
                        $status = "Active";
                        if($subscription->end_date)$status = "Leaving";
                        if($subscription->transaction)$paymentSubscription = PaymentSubscription::whereSubscriptionId($subscription->transaction->payment_subscription_id)->first();
                        else $paymentSubscription = null;
                        if($paymentSubscription)$currentActiveSubscriptionProgress = self::calculateMonthDiff($paymentSubscription->start_date,date('Y-m-d')).'m';
                        //if($subscription->plan_id == 1) $items[$index]['trial'] = 1;
                    }
                    if($subscription->status == "Cancelled"){
                        $status = "Cancelled";
                    }
                    if($subscription->start_date)$subscriptionStartDate = $subscription->start_date;
                    $cancellationDate = $subscription->cancelled_date;
                    $cancelledNow = $subscription->cancelled_now;
                    $stars = $subscription->quality_level;
                    if($subscription->cancelled_radio_reason)$reason = Subscription::CANCELLED_REASONS[$subscription->cancelled_radio_reason].' '.$subscription->cancelled_radio_reason_text;
                    if($reason == '') $reason = $subscription->cancelled_reason;
                    $feedback = $subscription->recommendation;
                    if($subscription->gateway == 'bank'){
                        $cardTypes[] = 'ACH';
                    }
                }
            }
            $record = $customer->findRecord();
            $objective = $customer->objective;
            if($objective == 'auto'){
                $objText='strong';
                if($customer['imc']>25)$objText = "cardio";
                else if($customer['imc']>18.5)$objText = "fit";
                $objective = $objText.'(auto)';
            }
            $cards = PaymentTocken::whereCustomerId($customer->id)->get();
            $cardRegistered = false;
            if(count($cards)>0){
                $cardRegistered = true;
                foreach($cards as $card){
                    if(in_array($card->type,$cardTypes)==false){
                        $cardTypes[] = $card->type;
                    }
                }
            }
            $firstDateDiff = '';
            if($customer->first_payment_date){
                $firstDateDiff = self::calculateMonthDiff($customer->first_payment_date,$customer->registration_date).'m';
            }
            $pay = null;
            $firstTransaction = null;
            $paidMonths = 0;
            $transactions = Transaction::whereCustomerId($customer->id)->whereStatus('Completed')->orderBy('done_date','ASC')->get();
            $lastPaymentSubscription=null;
            $nextPaymentMonths = 0;
            $consumedMonths = 0;
            $total = 0;
            $tenPercentcoupon = false;
            foreach( $transactions as $transaction){
                if($firstTransaction==null){
                    $firstTransaction = $transaction;
                }
                if($pay == null && $transaction->total>0){
                    $pay = $transaction;
                }
                $paymentSubscription = PaymentSubscription::whereSubscriptionId($transaction->payment_subscription_id)->first();
                if($paymentSubscription){
                    list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentSubscription->analyzeSlug();
                    if($pay)$paidMonths = $paidMonths + $frequency;
                    $lastPaymentSubscription = $paymentSubscription;
                    $endDate = $paymentSubscription->getEndDate($transaction);
                    if($cancellationDate && $cancelledNow == 'yes'){
                        if( $endDate < $cancellationDate){
                            $consumedMonths += $frequency;
                        }else {
                            $consumedMonths += self::calculateMonthDiff($endDate,$cancellationDate);
                        }
                    }else{
                        if( $endDate < date('Y-m-d H:i:s')){
                            $consumedMonths += $frequency;
                        }else {
                            $consumedMonths += self::calculateMonthDiff($endDate,date('Y-m-d H:i:s'));
                        }
                    } 
                }
                $total += $transaction->total;
                if(in_array($transaction->coupon_id,$tenPercentCouponsIds))$tenPercentcoupon = true;
            }
            if($lastPaymentSubscription && $lastPaymentSubscription->status == 'Active'){
                $paymentSubscriptionEndDate = $lastPaymentSubscription->findEndDate();
                if($paymentSubscriptionEndDate && $paymentSubscriptionEndDate>date('Y-m-d H:i:s')){
                    $nextPaymentMonths = self::calculateMonthDiff($paymentSubscriptionEndDate,date('Y-m-d H:i:s'));
                }
            }
            if($firstTransaction){
                $renewalCount = PaymentSubscription::whereCustomerId($customer->id)->where('start_date','>',$firstTransaction->done_date)->count();
                //if($renewalCount>0)$renewalCount-=1;
            }
            $question = "";
            switch($customer->qbligatory_question){
                case "recommend":
                    $question = "Me lo recomendaron";
                break;
                case "advertise":
                    $question = "Me llegó la publicidad";
                break;
                case "long":
                    $question = "Los conozco hace un tiempo";
                break;
            }
            $itemsArray[] = [$customer->id,
                $customer->first_name,
                $customer->last_name,
                $customer->email,
                $total>0?'Si':'No',
                $customer->active_email?'Si':'No',
                $customer->whatsapp_phone_number,
                $customer->active_whatsapp?'Si':'No',
                $customer->coupon&&$customer->hasSubscription()?$customer->coupon->code:'',
                $frequencyString,
                $pay?'Si':'No',//'Cliente Pago', //new payment have?Si/No
                $status, //'ACTIVO / INACTIVO',//new status
                $cardRegistered?'Si':'No',//'TARJETA PEGADA',//new payment card registered?Si/No
                implode(',',$cardTypes),//'TIPO DE PAGO',//new payment card type
                $customer->hasActiveSubscription()?'Si':'No',//'RECIBIENDO SERVICIO',//active subscription?Si/No
                $customer->registration_date, //'FECHA REGISTRO',//Registration Date
                $subscriptionStartDate,
                $customer->first_payment_date, //'FECHA PRIMER PAGO',//First Payment Date
                $firstDateDiff,// new the count of months between registeration date and first payment date.
                $cancellationDate,//new cancellation date
                //$cancellationReason, // new cancellation reason
                $stars,
                $reason,
                $feedback,
                $currentActiveSubscriptionProgress,//new the count of months of current active subscription
                $renewalCount,//new the count of renewaling
                $paidMonths,//new the count of total months paid
                $consumedMonths,//new the count of total months of using service
                $nextPaymentMonths,//new remaining the count of months until next payment
                $total,//new total money
                $tenPercentcoupon?'Si':'No',//new 10% lifetime discount accept or no accept
                $customer->gender=='Male'?'M':'F',
                $customer->initial_condition,
                $customer->current_condition,
                $customer->current_condition - $customer->initial_condition,
                $customer->training_place,
                $customer->current_height,
                $customer->initial_weight,
                $customer->current_weight,
                $customer->initial_weight - $customer->current_weight,
                $customer['initial_imc'],
                $customer['imc'],
                $customer['initial_imc'] - $customer['imc'],
                $record->benckmark_count,
                $objective,
                $customer['age'],
                strtoupper($customer->country_code),
                $record->video_count,
                $record->email_count,
                $record->blog_count,
                $record->session_count?$record->session_count*10:null,
                $record->edit_count,
                $record->contact_count,
                $question
            ];
        }
        $export = new CustomersExport([
            $itemsArray
        ]);
        return $export;
    }
    public function customerExport($tenPercentCouponsIds){
        $this->extends();
        $status = '';
        $frequencyString = '-';
        $cancellationDate = null;
        $currentActiveSubscriptionProgress = '';
        $renewalCount = 0;
        $cancelledNow = 'no';
        $stars = '';
        $reason = '';
        $feedback = '';
        $subscriptionStartDate = '';
        $cardTypes = [];
        if($this->user->active == 0){
            $status = "Disabled";
        }else{
            $status = "Inactive";
            foreach($this->subscriptions as $subscription){
                $frequencyString = $subscription->frequency;
                if($subscription->status == "Active"){
                    $status = "Active";
                    if($subscription->end_date)$status = "Leaving";
                    if($subscription->transaction)$paymentSubscription = \App\PaymentSubscription::whereSubscriptionId($subscription->transaction->payment_subscription_id)->first();
                    else $paymentSubscription = null;
                    if($paymentSubscription)$currentActiveSubscriptionProgress = Customer::calculateMonthDiff($paymentSubscription->start_date,date('Y-m-d')).'m';
                    //if($subscription->plan_id == 1) $items[$index]['trial'] = 1;
                }
                if($subscription->status == "Cancelled"){
                    $status = "Cancelled";
                }
                if($subscription->start_date)$subscriptionStartDate = $subscription->start_date;
                $cancellationDate = $subscription->cancelled_date;
                $cancelledNow = $subscription->cancelled_now;
                $stars = $subscription->quality_level;
                if($subscription->cancelled_radio_reason)$reason = \App\Subscription::CANCELLED_REASONS[$subscription->cancelled_radio_reason].' '.$subscription->cancelled_radio_reason_text;
                if($reason == '') $reason = $subscription->cancelled_reason;
                $feedback = $subscription->recommendation;
                if($feedback == '') $feedback = $subscription->cancelled_reason;
                if($subscription->gateway == 'bank'){
                    $cardTypes[] = 'ACH';
                }
            }
        }
        $record = $this->findRecord();
        $objective = $this->objective;
        if($objective == 'auto'){
            $objText='strong';
            if($this['imc']>25)$objText = "cardio";
            else if($this['imc']>18.5)$objText = "fit";
            $objective = $objText.'(auto)';
        }
        $cards = \App\PaymentTocken::whereCustomerId($this->id)->get();
        $cardRegistered = false;
        if(count($cards)>0){
            $cardRegistered = true;
            foreach($cards as $card){
                if(in_array($card->type,$cardTypes)==false){
                    $cardTypes[] = $card->type;
                }
            }
        }
        $firstDateDiff = '';
        if($this->first_payment_date){
            $firstDateDiff = Customer::calculateMonthDiff($this->first_payment_date,$this->registration_date).'m';
        }
        $pay = null;
        $firstTransaction = null;
        $paidMonths = 0;
        $transactions = \App\Transaction::whereCustomerId($this->id)->whereStatus('Completed')->orderBy('done_date','ASC')->get();
        $lastPaymentSubscription=null;
        $nextPaymentMonths = 0;
        $consumedMonths = 0;
        $total = 0;
        $tenPercentcoupon = false;
        foreach( $transactions as $transaction){
            if($firstTransaction==null){
                $firstTransaction = $transaction;
            }
            if($pay == null && $transaction->total>0){
                $pay = $transaction;
            }
            $paymentSubscription = \App\PaymentSubscription::whereSubscriptionId($transaction->payment_subscription_id)->first();
            if($paymentSubscription){
                list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentSubscription->analyzeSlug();
                if($pay)$paidMonths = $paidMonths + $frequency;
                $lastPaymentSubscription = $paymentSubscription;
                $endDate = $paymentSubscription->getEndDate($transaction);
                if($cancellationDate && $cancelledNow == 'yes'){
                    if( $endDate < $cancellationDate){
                        $consumedMonths += $frequency;
                    }else {
                        $consumedMonths += Customer::calculateMonthDiff($endDate,$cancellationDate);
                    }
                }else{
                    if( $endDate < date('Y-m-d H:i:s')){
                        $consumedMonths += $frequency;
                    }else {
                        $consumedMonths += Customer::calculateMonthDiff($endDate,date('Y-m-d H:i:s'));
                    }
                } 
            }
            $total += $transaction->total;
            if(in_array($transaction->coupon_id,$tenPercentCouponsIds))$tenPercentcoupon = true;
        }
        if($lastPaymentSubscription && $lastPaymentSubscription->status == 'Active'){
            $paymentSubscriptionEndDate = $lastPaymentSubscription->findEndDate();
            if($paymentSubscriptionEndDate && $paymentSubscriptionEndDate>date('Y-m-d H:i:s')){
                $nextPaymentMonths = Customer::calculateMonthDiff($paymentSubscriptionEndDate,date('Y-m-d H:i:s'));
            }
        }
        if($firstTransaction){
            $renewalCount = \App\PaymentSubscription::whereCustomerId($this->id)->where('start_date','>',$firstTransaction->done_date)->count();
            //if($renewalCount>0)$renewalCount-=1;
        }
        $question = "";
        switch($this->qbligatory_question){
            case "recommend":
                $question = "Me lo recomendaron";
            break;
            case "advertise":
                $question = "Me llegó la publicidad";
            break;
            case "long":
                $question = "Los conozco hace un tiempo";
            break;
        }
        return ['ID'=>$this->id,
            'Nombre'=>$this->first_name,
            'Last Name'=>$this->last_name,
            'Correo'=>$this->email,
            'Cliente (SI / NO )'=>$total>0?'Si':'No',// SI → if customer pay at least one cent,No → if customer has not paid
            'Quiere verlo en el correo'=>$this->active_email?'Si':'No',//new active_email
            'Whatsapp'=>$this->whatsapp_phone_number,
            'Quiere recibir WA'=>$this->active_whatsapp?'Si':'No',//new active_whatsapp
            'Código de entrada'=>$this->coupon&&$this->hasSubscription()?$this->coupon->code:'',
            'PLAN DE SUSCRIPCIÓN'=>$frequencyString,//new subscription frequency
            'Cliente Pago'=>$pay?'Si':'No',// new payment have?Si/No
            'estado'=>$status,//new status
            'TARJETA PEGADA'=>$cardRegistered?'Si':'No',//new payment card registered?Si/No
            'TIPO DE PAGO'=>implode(',',$cardTypes),//new payment card type
            'RECIBIENDO SERVICIO'=>$this->hasActiveSubscription()?'Si':'No',//active subscription?Si/No
            'FECHA REGISTRO'=>$this->registration_date,//Registration Date
            'Inicio de suscripción'=>$subscriptionStartDate,//date when subscription start
            'FECHA PRIMER PAGO'=>$this->first_payment_date,//First Payment Date
            'MESES ENTRE PAGO Y REGISTRO'=>$firstDateDiff,// new the count of months between registeration date and first payment date.
            'FECHA CANCELACIÓN SUSCRIPCIÓN'=>$cancellationDate,//new cancellation date
            //'Cancellation',//cancellation reason
            'Stars'=>$stars,
            'Motivo'=>$reason,
            'Feedback'=>$feedback,
            'Tiempo Activo En Suscripción Meses'=>$currentActiveSubscriptionProgress,//new the count of months of current active subscription
            'Cantidad de Renovaciones'=>$renewalCount,//new the count of renewaling
            'MESES PAGADOS'=>$paidMonths,//new the count of months paid
            'MESES SERVICIO RECIBIDO'=>$consumedMonths,//new the count of months of using service
            'MESES PARA RENOVACIÓN'=>$nextPaymentMonths,//new remaining the count of months until next payment
            'VENTAS ACUMULADAS'=>$total,//new total money
            'DESC SALIDA'=>$tenPercentcoupon?'Si':'No',//new 10% lifetime discount accept or no accept
            'Sexo'=>$this->gender=='Male'?'M':'F',//gender 
            'Condición física'=>$this->initial_condition,/*Condición Física Inicial*/
            'Condición Física Actual'=>$this->current_condition,
            'Diferencia Nivel Físico'=>$this->current_condition - $this->initial_condition,
            'Lugar de Entrenamiento'=>$this->training_place,//training_place
            'Altura'=>$this->current_height,//Height
            'Peso Inicial'=>$this->initial_weight,
            'Peso Actual'=>$this->current_weight,
            'Diferencia de Peso'=>$this->initial_weight - $this->current_weight,
            'IMC inicial'=>$this['initial_imc'],
            'IMC actual'=>$this['imc'],
            'Diferencia IMC'=>$this['initial_imc'] - $this['imc'],
            'Workouts Ingrsados'=>$record->benckmark_count,
            'Objetivo'=>$objective,
            'Edad'=>$this['age'],
            'País'=>strtoupper($this->country_code),// country
            'Click on videos'=>$record->video_count,
            'Click on email links'=>$record->email_count,
            'Click on Blogs'=>$record->blog_count,
            'Tiempo activo en website'=>$record->session_count?$record->session_count*10:null,
            'Actualizaciones totales dentro del perfil'=>$record->edit_count,
            'contact request'=>$record->contact_count,
            '¿Cómo nos conociste?'=>$question
        ];
    }
    public function currentDate(){
        $userTimezone = new \DateTimeZone($this->timezone);
        $objDateTime = new \DateTime('NOW');
        $objDateTime->setTimezone($userTimezone);
        if($objDateTime->format('H')>18){
            $objDateTime->modify('+1 day');
        }
        return $objDateTime->format('Y-m-d');
    }
    public function getPayAmount($amount,$coupon,$transaction = null){
        $partnerDiscount = $this->findPartnerDiscount();
        if ($coupon) {
            $amount = $coupon->getDiscountedAmount($amount,$partnerDiscount,$transaction);
        }else{
            if( $partnerDiscount ) $amount = round($amount * (100 - $partnerDiscount)/100,2);
        }
        return $amount;
    }
    public function setFreeSubscription(){
        if($this->coupon && in_array($this->coupon->type, Coupon::INVITATION_TYPES)){
            $possibleSubscription = false;
            switch($this->coupon->type){
                case 'InvitationEmail':
                    if($this->coupon){
                        $possibleSubscription = true;
                    }
                break;
                case 'InvitationCode':
                    if($this->coupon->max_user_count&&$this->coupon->max_user_count>$this->coupon->current_user_count || $this->coupon->max_user_count==null){
                        $this->coupon->current_user_count++;
                        $this->coupon->save();
                        $possibleSubscription = true;
                    }
                break;
            }
            if($possibleSubscription){
                $subscription = new Subscription;
                $subscription->plan_id = 1;// from service;
                $subscription->payment_plan_id = $this->coupon->type;
                $subscription->start_date = date("Y-m-d H:i:s");
                $subscription->coupon_id = $this->coupon_id;
                $subscription->gateway = 'nmi';
                $subscription->customer_id = $this->id;
                $subscription->save();
            }
        }
    }
    public function getPeople(){//public profile and followers
        $follows = DB::table("follows")->select("*")->where('customer_id',$this->id)->whereIn('status',['accepted'])->get();
        $followerIds = [];
        foreach($follows as $follow){
            $followerIds[] = $follow->follower_id;
        }
        $where = Customer::with("user")->with(['mutedRelations' => function ($query) {
            $query->where('customers_relations.follower_id', $this->id);
            $query->where('customers_relations.status', 'muted');
        }]);
        $where->where(function($query){
            $query->whereHas('user', function($q){
                $q->where('active','=','1');
            });
            $query->whereHas('subscriptions', function($q){
                $q->where('status','=',"Active");
            });
        });
        $publicProfiles = [];//public and following private profiles
        $provateProfiles = [];
        $customers = $where->get();
        foreach($customers as $customer){
            $customer->display = $customer->first_name.' '.$customer->last_name;
            $customer->getAvatar();
            $customer->chat_id = $customer->user->chat_id;
            unset($customer->user);
            if($customer->mutedRelations->count()>0){
                $customer['relation'] = $customer->mutedRelations[0]->pivot->status;
            }
            if($customer->profile=='private' && in_array($followerIds, $customer->id)==false){
                $provateProfiles[] = $customer;
            }else{
                $publicProfiles[] = $customer;
            }
        }
        return [$publicProfiles, $provateProfiles];
    }
    public function generateUsername(){
        $names = explode('@',$this->email);
        $username = $names[0];
        $validator = Validator::make(['username'=>$username], array('username'=>['unique:customers,username,'.$this->id]));
        if ($validator->fails()) {
            $i = 0;
            do{
                $username = $names[0].$i;
                $validator = Validator::make(['username'=>$username], array('username'=>['unique:customers,username,'.$this->id]));
                $i++;
            }while($validator->fails());
        }
        $this->username = $username;
    }
    public function getAvatar(){
        $user = $this->user;
        if($user->avatar){
            $data = pathinfo($user->avatar);
            $avatarFile = $data['dirname']."/avatar/".$data['filename'].".".$data['extension'];
            $this['avatarUrls'] = [
                'max'=>secure_url("storage/".$user->avatar),
                'large'=>secure_url("storage/".$user->avatar),
                'medium'=>secure_url("storage/".$user->avatar),
                'small'=>secure_url("storage/".$avatarFile),
            ];
            if(config('app.env') === 'local'){
                $this['avatarUrls'] = [
                    'max'=>url("storage/".$user->avatar),
                    'large'=>url("storage/".$user->avatar),
                    'medium'=>url("storage/".$user->avatar),
                    'small'=>url("storage/".$avatarFile),
                ];
            }
        }else{
            if($this->gender=="Male"){
                $this['avatarUrls'] = [
                    'max'=>secure_url("storage/media/avatar/X-man-large.jpg"),
                    'large'=>secure_url("storage/media/avatar/X-man-large.jpg"),
                    'medium'=>secure_url("storage/media/avatar/X-man-medium.jpg"),
                    'small'=>secure_url("storage/media/avatar/X-man-small.jpg"),
                ];
                if(config('app.env') === 'local'){
                    $this['avatarUrls'] = [
                        'max'=>url("storage/media/avatar/X-man-large.jpg"),
                        'large'=>url("storage/media/avatar/X-man-large.jpg"),
                        'medium'=>url("storage/media/avatar/X-man-medium.jpg"),
                        'small'=>url("storage/media/avatar/X-man-small.jpg"),
                    ];
                }
            }else{
                $this['avatarUrls'] = [
                    'max'=>secure_url("storage/media/avatar/X-woman-large.jpg"),
                    'large'=>secure_url("storage/media/avatar/X-woman-large.jpg"),
                    'medium'=>secure_url("storage/media/avatar/X-woman-medium.jpg"),
                    'small'=>secure_url("storage/media/avatar/X-woman-small.jpg"),
                ];
                if(config('app.env') === 'local'){
                    $this['avatarUrls'] = [
                        'max'=>url("storage/media/avatar/X-woman-large.jpg"),
                        'large'=>url("storage/media/avatar/X-woman-large.jpg"),
                        'medium'=>url("storage/media/avatar/X-woman-medium.jpg"),
                        'small'=>url("storage/media/avatar/X-woman-small.jpg"),
                    ];
                }
            }
        }
    }
    public function getNewsfeed($fromId,$suggested){
        $companyIds = [];
        if($suggested == 0){
            $expirationDate = $this->currentDate();
            $companies = Company::whereHas('countries',function($query){
                $query->where('country','=',strtoupper($this->country_code));
                })
                ->whereStatus('active')
                ->where('is_all_countries','=','no')
                ->orWhere('is_all_countries','=','yes')
                ->whereHas('products',function($query) use ($expirationDate) {
                    $query->where('status', '=', "Active")
                        ->where('expiration_date', '>=', $expirationDate);
                })->get();
            foreach($companies as $company){
                $companyIds[] = $company->id;
            }    
        }
        $profileManagerIds = $this->getProfileMangerIds();
        $where = \App\Models\Post::where(function($query) use ($suggested, $companyIds, $profileManagerIds){
            // with(['customer','medias']);
            $query->whereHas('customer',function($query) use ($suggested, $profileManagerIds){
                if($suggested == 0){
                    $query->whereHas('followers',function($q){
                        $q->where("follows.follower_id",$this->id); 
                    });
                }else{
                    $query->whereDoesntHave('followers',function($q){
                        $q->where("follows.follower_id",$this->id); 
                    });
                }
                $query->whereDoesntHave('mutedRelations',function($q){
                    $q->where("customers_relations.follower_id",$this->id); 
                });
                if($suggested == 0){
                    $query->orwhere('customer_id','=',$this->id);
                    $query->orWhereIn('customer_id',$profileManagerIds);
                }else{
                    $query->where('customer_id','!=',$this->id);
                    $query->whereNotIn('customer_id',$profileManagerIds);
                }
            });
            if($suggested == 0){
                $query->orWhere(function($query){
                    $query->whereCustomerId(0)->whereIn('type',['benchmark', 'blog', 'evento']);
                });
                $query->orWhere(function($query){
                    $query->whereCustomerId(0)->whereIn('type',['join'])->where('object_id','!=',$this->id);
                });
                $query->orWhere(function($query) use ($companyIds){
                    $query->whereCustomerId(0)->whereIn('type',['shop'])->whereIn('object_id',$companyIds);
                });
            }
        });

        /** get article types post such as shop, benchmark, blog, evento */
        $where->whereDoesntHave('readingCustomers',function($query){
            $query->where("reading_posts.customer_id",$this->id);
        });
        if($fromId>0){
            $where->where('id','<',$fromId);
        }
        $result = $where->whereStatus('1')->orderBy('id','desc')->limit(9)->get();
        /** get birthdays */
        if(Cache::has('activeCustomerIds')){
            $activeCustomerIds = Cache::get('activeCustomerIds');
        }else{
            $customers = Customer::where(function($query){
                $query->whereHas('user', function($q){
                    $q->where('active','=','1');
                });
                $query->whereHas('subscriptions', function($q){
                    $q->where('status','=',"Active")->whereNull('end_date');
                });
            })->get();
            $activeCustomerIds = [];
            foreach($customers as $customer){
                $activeCustomerIds[] = $customer->id;
            }
            // Cache::forever('activeCustomerIds', $activeCustomerIds);
            Cache::add('activeCustomerIds', $activeCustomerIds, 3600*24);
        }
        $followings = DB::table("follows")->select("*")->where('follower_id',$this->id)->whereIn('status',['accepted'])->get();
        $followingIds = [];
        foreach($followings as $following){
            $followingIds[] = $following->customer_id;
        }
        $posts = [];
        if(($fromId<0 || $fromId == null ) && $suggested == '0'){
            $workoutPost = $this->generateWorkoutPost();
            if($workoutPost)$posts = [$workoutPost];
        }
        foreach($result as $index=>$post){
            if(($fromId==null || $fromId<0) && $index == 0 ){
                $birthdays = $this->generateBirthdayPosts(Carbon::now(), $result[$index]->created_at, $activeCustomerIds,$followingIds,$suggested);
                if(count($birthdays)>0)$posts = array_merge($posts, $birthdays);
            }
            if($index<8 && isset($result[$index+1])){
                $post->extend(null, $this->user);
                $posts[] = $post;
                $birthdays = $this->generateBirthdayPosts($post->created_at, $result[$index+1]->created_at, $activeCustomerIds,$followingIds,$suggested);
                if(count($birthdays)>0)$posts = array_merge($posts, $birthdays);
            }
        }
        return [$posts, isset($result[8])];
    }
    private function getProfileMangerIds(){
        if(Cache::has('profileManagerIds')){
            $profileManagerIds = Cache::get('profileManagerIds');
        }else{
            $users = \App\User::whereHas("roles", function($q){ $q->where("name", "profileManager"); })->get();
            $profileManagerIds = [];
            foreach($users as $user){
                if($user->customer)$profileManagerIds[] = $user->customer->id;
            }
            Cache::forever('profileManagerIds', $profileManagerIds);
        }
        return $profileManagerIds;
    }
    public function getOldNewsfeed($fromId){
        $profileManagerIds = $this->getProfileMangerIds();
        $where = \App\Models\Post::with(['customer','medias']);
        $where->whereHas('customer',function($query) use ($profileManagerIds) {
            $query->whereHas('followers',function($q){
                $q->where("follows.follower_id",$this->id); 
            });
            $query->whereDoesntHave('mutedRelations',function($q){
                $q->where("customers_relations.follower_id",$this->id); 
            });
            $query->orwhere('customer_id','=',$this->id);
            $query->orWhereIn('customer_id',$profileManagerIds);
        });
        $where->whereHas('readingCustomers',function($query){
            $query->where("reading_posts.customer_id",$this->id);
        });
        $where->where('type','!=','join');
        if($fromId>0){
            $where->where('id','<',$fromId);
        }
        $result = $where->where('posts.status',1)->orderBy('posts.id','desc')->limit(9)->get();
        $posts = [];
        foreach($result as $index=>$post){
            if($index<8 && isset($result[$index+1])){
                $post->extend(null, $this->user);
                $posts[] = $post;
            }
        }
        return [$posts, isset($result[8])];
    }
    public function generateWorkoutPost(){
        setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
        $userTimezone = new \DateTimeZone($this->timezone);
        $objDateTime = new \DateTime('NOW');
        $objDateTime->setTimezone($userTimezone);
        $hour = $objDateTime->format('H');
        if( $hour >= 19){
            $workout = $this->getSendableWorkout(1);
        }else{
            $workout = $this->getSendableWorkout(0);
        }
        // $workout = $this->findSendableWorkout('2021-02-23');
        $post = null;
        if($workout){
            $done = \App\Done::whereCustomerId($this->id)->whereDoneDate($workout['publish_date'])->first();
            if($done == null){
                $text = '';
                if($workout['blog']){
                    $text = $workout['content'];
                }else{
                    foreach($workout['blocks'] as $block){
                        if($block['slug'] === 'comentario'){
                            foreach($block['content'] as $content){
                                if(isset($content['before_content']))$text .= $content['before_content'];
                                if(isset($content['after_content']))$text .= $content['after_content'];
                            }
                        }
                    }
                }
                $post = ['id'=>$workout['publish_date'].'-w',
                'type'=>'workout-post',
                'title'=>$workout['date'],
                'content'=>$text,
                'contentType'=>'html',
                ];
                if(isset($workout['image_path'])){
                    try {
                        $data = getimagesize($workout['image_path']);
                        if(isset($data[0])){
                            $post['medias'] = [[
                                'post_id'=>$workout['publish_date'].'-w',
                                'url'=>$workout['image_path'],
                                'type'=>'image',
                                'width'=>$data[0], 
                                'height'=>$data[0],
                            ]];
                        }
                    } catch (\Exception $e) {

                    }
                }                        
            }
        }
        return $post;
    }
    private function generateBirthdayPosts($firstDate, $secondDate, $activeCustomerIds,$followingIds,$suggested){
        // get id(date) customerIds from date;
        $startDate = $firstDate->format("Y-m-d")." 07:00:00";
        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDate, 'America/Panama');
        $endDate = $secondDate->format("Y-m-d")." 07:00:00";
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate, 'America/Panama');
        $posts = [];
        $date = $firstDate->format("Y-m-d")." 07:00:00";
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $date, $this->timezone);
        if($date->greaterThan($firstDate)){
            $date->add(-1,'day');
        }
        setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
        while($date->lessThan($startDate) && $date->greaterThanOrEqualTo($endDate)){
            $birthday = $date->format("m-d");
            // if($birthday == '04-16'){
                $where = Customer::with('user')
                ->where('birthday','like',"%".$birthday)
                ->whereIn('id',$activeCustomerIds);
                if($suggested == 0 ){
                    $where->whereIn('id',$followingIds);
                }else{
                    $where->whereNotIn('id',$followingIds);
                }
                $where->where('id','!=',$this->id);
                $customers = $where->get();
                if($customers->count()>0){
                    $spanishDate = iconv('ISO-8859-2', 'UTF-8', strftime("%d de %B", strtotime($date->format("Y-m-d"))));
                    foreach($customers as $customer){
                        $customer->getAvatar();
                        $customer->chat_id = $customer->user->chat_id;
                        unset($customer->user);
                        if($customer->mutedRelations->count()>0){
                            $customer['relation'] = $customer->mutedRelations[0]->pivot->status;
                        }            
                    }
                    $posts[] = ['id'=>$date->format("Y-m-d").'-'.$suggested,'type'=>'birthday','label'=>$spanishDate,'customers'=>$customers];
                }
            // }
            $date->add(-1,'day');
        }
        return $posts;
    }
    public function getSocialDetails($authId = null){
        if($this->avatarUrls == null)$this->getAvatar();
        //following
        //followers
        if($this->id == $authId){
            $this['followers'] = DB::table("follows")->select("*")->where('customer_id',$this->id)->whereIn('status',['pending','accepted'])->get();
            $this['followings'] = DB::table("follows")->select("*")->where('follower_id',$this->id)->whereIn('status',['pending','accepted'])->get();
        }else{
            $this['followers'] = DB::table("follows")->select("*")->where('customer_id',$this->id)->where('status','accepted')->get();
            $this['followings'] = DB::table("follows")->select("*")->where('follower_id',$this->id)->where('status','accepted')->get();
            if($authId){
                $this['following'] = DB::table("follows")->select("*")->where('follower_id',$authId)->where('customer_id',$this->id)->first();
            }
        }
        $this['display'] = $this->first_name." ".$this->last_name;
        //posts
        $posts =  \App\Models\Post::whereCustomerId($this->id)->whereStatus(1)->get();
        $this['postCount'] = $posts->count();
        $this['relation'] = false;
        if($authId){
            $relation = DB::table("customers_relations")->select("*")->where('follower_id',$authId)->where('customer_id',$this->id)->first();
            if($relation)$this['relation'] = $relation->status;
        }
        $chatBlocked = DB::table("customers_relations")->select("*")->where('customer_id',$this->id)->orWhere('follower_id',$this->id)->get();
        $chatBlockIds = [];
        foreach($chatBlocked as $record){
            if($record->follower_id == $this->id){
                $chatBlockIds[] = $record->customer_id;
            }
            if($record->customer_id == $this->id){
                $chatBlockIds[] = $record->follower_id;
            }
        }
        $chatBlockers = Customer::with('user')->whereIn('id',$chatBlockIds)->get();
        $blockedChatIds = [];
        foreach($chatBlockers as $customer){
            if($customer->user->chat_id)$blockedChatIds[] = $customer->user->chat_id;
        }
        $this['blockedChatIds'] = $blockedChatIds;
        //with profile manager?
        $this['is_manager'] = $this->user->hasRole('profileManager');
    }
    public function isConnecting($customer){
        if($this->id == $customer->id) return true;
        if($customer->profile=='public') return true;
        $block = DB::table("customers_relations")->select("*")->where('customer_id',$customer->id)->where('follower_id',$this->id)->first();
        if($block) return false;
        $item = DB::table("follows")->select("*")->where('customer_id',$customer->id)->where('follower_id',$this->id)->whereIn('status',['accepted'])->first();
        if($item)return true;
        return false;
    }
    public function findCustomerProfile($request){
        $profile = true;
        if($request->exists('customer_id')){
            $customer = Customer::find($request->customer_id);
            $profile = $this->isConnecting($customer);
        }
        return $profile;
    }
    public function isFirstTransaction($transaction){
        $transactions = Transaction::whereCustomerId($this->id)->get();
        if($transactions->count() == 1){
            if($transactions[0]->id == $transaction->id){
                $freePaymentSubscription = PaymentSubscription::where('plan_id','like','nmi-1%')->first();
                if($freePaymentSubscription) return false;
                return true;
            }
        }
        return false;
    }
}
