<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Twilio\Rest\Client;
use App\Jobs\SendEmail;
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
            'email'=>'required|max:255|unique:users,email,'.$user_id,
            'customer_email'=>'required|max:255|unique:customers,email,'.$customer_id,
            'first_name'=>['required','max:255'],
            'last_name'=>['required','max:255'],
            'active_email'=>['required'],
            'active_whatsapp'=>['required'],
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
    public function recentWorkouts(){
        $workouts = [];
        if($this->hasActiveSubscription()){
            if($this->activeWorkoutSubscription==null){
                $subscription = Subscription::whereCustomerId($this->id)->where(function($query){
                    $query->whereHas('plan', function($q){
                        $q->where('service_id','=','1');
                    });
                })->first();
                $this->activeWorkoutSubscription = $subscription;
            }
            $userTimezone = new \DateTimeZone($this->timezone);
            $objDateTime = new \DateTime('NOW');
            $objDateTime->setTimezone($userTimezone);
            $fiveHours = \DateInterval::createFromDateString('+5 hours');
            $objDateTime->add($fiveHours);
            $today = $objDateTime->format('Y-m-d');
            list($workoutCondition,$weightsCondition,$objective,$gender) = $this->findWorkoutFilters($today);
            $workouts = [];
            $i = 0;
            $contentFilters = ['Workout','Extra','Descanso Activo','Descanso Blog'];
            setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
            while(count($workouts)<3){
                $userTimezone = new \DateTimeZone($this->timezone);
                $objDateTime = new \DateTime('NOW');
                $objDateTime->setTimezone($userTimezone);
                setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
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
            SendEmail::dispatch($this,new \App\Mail\Workout($workout['date'],$workout['content'],$workout['blog']));
            //Mail::to($this->email)->send(new \App\Mail\Workout($workout['date'],$workout['content'],$workout['blog']));
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
}
