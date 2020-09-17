<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Twilio\Rest\Client;
use App\Jobs\SendEmail;
use App\Exports\CustomersExport;
use Mail;

class Customer extends Model
{
    protected $fillable = ['first_name','last_name','gender','birthday','whatsapp_phone_number','policy','status','country','country_code'];    
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
    public function assign($request){
        foreach($this->fillable as $property){
            if($request->exists($property)){
                $this->{$property} = $request->input($property);
            }
        }
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
    }
    public function search(){
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
        $objDateTime->add($fiveHours);
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
        $level = LevelTest::whereCustomerId($this->id)->orderBy('recording_date','desc')->first();
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
                    $workouts['current'] = ['date'=>$workout['date'],'short_date'=>$workout['short_date'],'blocks'=>$workout['blocks'],'content'=>$workout['content'],'dashboard'=>$workout['dashboard'],'blog'=>$workout['blog'],'read'=>$this->readDone($today),'today'=>$today];
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
                        break;
                    }
                    $workout = $this->findSendableWorkout($today);
                    if($workout){
                        $workouts['previous'] = ['date'=>$workout['date'],'blocks'=>$workout['blocks'],'content'=>$workout['content'],'dashboard'=>$workout['dashboard'],'blog'=>$workout['blog'],'read'=>$this->readDone($today),'today'=>$today];
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
                        break;
                    }
                    $workout = $this->findSendableWorkout($today);
                    if($workout){
                        $workouts['next'] = ['date'=>$workout['date'],'blocks'=>$workout['blocks'],'content'=>$workout['content'],'dashboard'=>$workout['dashboard'],'blog'=>$workout['blog'],'read'=>$this->readDone($today),'today'=>$today];
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
            }
        }
    }
    public function removeFriendShip(){
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
                    $workouts[] = ['date'=>$workout['date'],'content'=>$workout['content'],'dashboard'=>$workout['dashboard'],'blog'=>$workout['blog'],'read'=>$this->readDone($today),'today'=>$today];
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
    public function findMedal(){
        $doneworkouts = $this->done();
        $workoutCount = $doneworkouts->count();
        $fromWorkout = 0;
        $fromWorkoutImage = null;
        $toWorkout = 0;
        $toWorkoutImage = null;
        $levels = Medal::orderBy('count')->get();
        foreach($levels as $index=>$level){
            $toWorkout = $level->count;
            $toWorkoutImage = url('storage/'.$level->image);
            if($workoutCount>$level->count){
            }else{
                if(isset($levels[$index-1])){
                    $fromWorkout = $levels[$index-1]['count'];
                    $fromWorkoutImage = url('storage/'.$levels[$index-1]['image']);
                }
                break;
            }
        }
        return ['fromWorkout'=>$fromWorkout,
            'fromWorkoutImage'=>$fromWorkoutImage,
            'toWorkout'=>$toWorkout,
            'toWorkoutImage'=>$toWorkoutImage,
            'workoutCount'=>$workoutCount];
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
        return env('APP_URL').Coupon::REFERRAL_URL.$code;
    }
    public function findPartners(){
        $partners = Customer::whereFriendId($this->id)->get();
        foreach($partners as $partner){
            if($partner->user->avatar){
                $partner['avatarUrls'] = [
                    'max'=>url("storage/".$partner->user->avatar),
                    'large'=>url("storage/".$partner->user->avatar),
                    'medium'=>url("storage/".$partner->user->avatar),
                    'small'=>url("storage/".$partner->user->avatar),
                ];
            }else{
                if($partner->gender=="Male"){
                    $partner['avatarUrls'] = [
                        'max'=>url("storage/media/avatar/X-man-large.jpg"),
                        'large'=>url("storage/media/avatar/X-man-large.jpg"),
                        'medium'=>url("storage/media/avatar/X-man-medium.jpg"),
                        'small'=>url("storage/media/avatar/X-man-small.jpg"),
                    ];
                }else{
                    $partner['avatarUrls'] = [
                        'max'=>url("storage/media/avatar/X-woman-large.jpg"),
                        'large'=>url("storage/media/avatar/X-woman-large.jpg"),
                        'medium'=>url("storage/media/avatar/X-woman-medium.jpg"),
                        'small'=>url("storage/media/avatar/X-woman-small.jpg"),
                    ];
                }
            }
        }
        return $partners;
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
                    $cancellationDate = $subscription->cancelled_date;
                    $cancelledNow = $subscription->cancelled_now;
                    $stars = $subscription->quality_level;
                    if($subscription->cancelled_radio_reason)$reason = Subscription::CANCELLED_REASONS[$subscription->cancelled_radio_reason].' '.$subscription->cancelled_radio_reason_text;
                    $feedback = $subscription->recommendation;
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
            $cardTypes = [];
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
    public function currentDate(){
        $userTimezone = new \DateTimeZone($this->timezone);
        $objDateTime = new \DateTime('NOW');
        $objDateTime->setTimezone($userTimezone);
        return $objDateTime->format('Y-m-d');
    }
}
