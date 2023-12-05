<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Service extends Model
{
    protected $fillable = ['title','description'];    
    private $pageSize;
    private $pageNumber;
    public static function validateRules(){
        return array(
            'title'=>'required|max:255',
        );
    }
    private static $searchableColumns = ['title','description'];
    public function memberships(){
        return $this->hasMany('App\SubscriptionPlan');
    }
    public function search(){
        $where = Service::where(function($query){
            $index = 0;
            foreach(self::$searchableColumns as $property){
                if($this->{$property}!=null){
                    if($index == 0)$query->Where($property,'like','%'.$this->{$property}.'%');
                    else $query->orWhere($property,'like','%'.$this->{$property}.'%');
                    $index++;
                }
            }
        });
        //whereIn('status',$this->statuses)
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $index=> $service){
            $s = Service::find($service->id);
            $memberships = $s->memberships;
            foreach($memberships as $membership){
                if($membership->type=="Paid")$paid = $membership;
            }
            if(isset($paid)){
                $items[$index]['monthly'] = $paid->month_1;
                if($items[$index]['monthly'] == null)$items[$index]['monthly']="";
                $items[$index]['quarterly'] = $paid->month_3;
                if($items[$index]['quarterly'] == null)$items[$index]['quarterly']="";
                $items[$index]['semiannual'] = $paid->month_6;
                if($items[$index]['semiannual'] == null)$items[$index]['semiannual']="";
                $items[$index]['yearly'] = $paid->month_12;
                if($items[$index]['yearly'] == null)$items[$index]['yearly']="";
                $items[$index]['frequency'] = $paid->frequency;
                if($items[$index]['frequency'] == null)$items[$index]['frequency']="";
            }
        }        

        return $response;
    }
    public function getMemberships(){
        $memberships = $this->memberships;
        foreach($memberships as $membership){
            if($membership->type=="Paid")$paid = $membership;
            if($membership->type=="Free")$free = $membership;
        }
        if(isset($paid)){
            $this['monthly'] = $paid->month_1;
            if($this['monthly'] == null)$this['monthly']="";
            $this['quarterly'] = $paid->month_3;
            if($this['quarterly'] == null)$this['quarterly']="";
            $this['semiannual'] = $paid->month_6;
            if($this['semiannual'] == null)$this['semiannual']="";
            $this['yearly'] = $paid->month_12;
            if($this['yearly'] == null)$this['yearly']="";
            $this['frequency'] = $paid->frequency;
            if($this['frequency'] == null)$this['frequency']="";
            $this['bank_1'] = $paid->bank_1;
            $this['bank_3'] = $paid->bank_3;
            $this['bank_6'] = $paid->bank_6;
            $this['bank_12'] = $paid->bank_12;
            $this['bank_fee'] = $paid->bank_fee;
        }
        if(isset($free)){
            $this['free_duration'] = $free->free_duration;
            if($this['free_duration'] == null)$this['free_duration']="";
        }
        if($this->photo_path)  $this->photo_path = secure_url('storage/'.$this->photo_path);
    }
    public function findWorkoutDates($year){
        return Workout::findDates($year);
    }
    public function findWeeklyWorkouts($date){
        return Workout::findByWeek($this->id,$date);
    }
    public function findPendingWorkouts(){
        return StaticWorkout::findContents();
    }
    public function assign($request){
        foreach($this->fillable as $property){
            if($request->exists($property)){
                $this->{$property} = $request->input($property);
            }
        }
        $memberships = $this->memberships;
        foreach($memberships as $membership){
            if($membership->type=="Paid")$paid = $membership;
            if($membership->type=="Free")$free = $membership;
        }
        $savePaid = false;
        if($request->exists('monthly')){
            $paid->month_1 = $request->input('monthly');
            $savePaid = true;
        }else{
            //print_r('not exist');
        }
        if($request->exists('quarterly')){
            $paid->month_3 = $request->input('quarterly');
            $savePaid = true;
        }
        if($request->exists('semiannual')){
            $paid->month_6 = $request->input('semiannual');
            $savePaid = true;
        }
        if($request->exists('yearly')){
            $paid->month_12 = $request->input('yearly');
            $savePaid = true;
        }
        if($request->exists('frequency')){
            $paid->frequency = $request->input('frequency');
        }
        if($request->exists('bank_1')){
            $paid->bank_1 = $request->input('bank_1');
        }
        if($request->exists('bank_1')){
            $paid->bank_3 = $request->input('bank_3');
        }
        if($request->exists('bank_1')){
            $paid->bank_6 = $request->input('bank_6');
        }
        if($request->exists('bank_1')){
            $paid->bank_12 = $request->input('bank_12');
        }
        if($request->exists('bank_fee')){
            $paid->bank_fee = $request->input('bank_fee');
        }
        if($savePaid)$paid->save();
        if($free && $request->exists('free_duration')){
            $free->free_duration = $request->input('free_duration');
            $free->save();
        }
    }
    public function assignSearch($request){
        foreach(self::$searchableColumns as $property){
            $this->{$property} = $request->input($property);
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public function setPayments(){
        if($this->paypal_product_id===null){
            //PayPalProduct::create($this);
        }
    }
}
