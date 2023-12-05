<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use App\Mail\CouponTrialBefore;
use App\Mail\CouponTrialAfter;
use App\Mail\CouponReport;
use Mail;

class Coupon extends Model
{
    const TRIAL_BEFORE = 'trial_before';
    const TRIAL_AFTER = 'trial_after';
    const RENEWAL = 'renewal';
    const FIRST_PAY = 'renewal';
    const COUPON_URL = '/pricing?coupon=';
    const REFERRAL_URL = '/signup?referral=';
    const EMAIL_INVITATION_URL = '/signup?ein=';
    const DEFAULT = null;
    const REFERRAL_NAME = 'referral';
    const INVITATION_TYPES = ['InvitationEmail','InvitationCode'];
    protected $fillable = ['code','name','type','mail','discount','renewal','form','expiration','max_user_count'];
    private $pageSize;
    private $statuses;
    private $pageNumber;
    public static function validateRules($id=null){
        return array(
            'code'=>'required|max:255|unique:coupons,code,'.$id,
            'name'=>'required|max:255',
            'mail'=>'nullable|email|max:255',
            'discount'=>'nullable|numeric|max:100',
            'expiration'=>'nullable',
            'max_user_count'=>'nullable|integer',
        );
    }
    private static $searchableColumns = ['search','type'];
    public function customers(){
        return $this->hasMany('App\Customer');
    }
    public function assign($request){
        foreach($this->fillable as $property){
            if($request->exists($property)){
                $this->{$property} = $request->input($property);
            }
        }
    }
    public function search(){
        $where = Coupon::whereIn('status',$this->statuses)
            ->where(function($query){
            if($this->search!=null){
                $query->Where('code','like','%'.$this->search.'%');
                $query->orWhere('name','like','%'.$this->search.'%');
                $query->orWhere('mail','like','%'.$this->search.'%');
            }
        });
        if($this->type){
            if($this->type == 'invitations'){
                $where->whereIn('type',Coupon::INVITATION_TYPES);
            }else{
                $where->where('type',$this->type);
            }
        }else{
            $where->whereIn('type',['Public']);
        }
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('id', 'ASC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $index=> $coupon){
            $date = explode(' ',$coupon->created_at);
            $items[$index]['created_date'] = $date[0];
            $items[$index]['non_active'] = $coupon->getNonActiveCustomers();
            $items[$index]['active'] = $coupon->getActiveCustomers();
            $items[$index]['paid'] = $coupon->getPaidCustomers();
            $items[$index]['current_month'] = $coupon->getTotal(date('Y-m').'-01');
        }        
        return $response;
    }
    public function getTotal($fromDate){
        $transactions = Transaction::where('done_date','>=',$fromDate)->where(function($query){
            $query->whereHas('customer', function ($q) {
                $q->whereCouponId($this->id);
            });
        });
        $total = 0;
        foreach($transactions as $transaction){
            $total +=$transaction->total;
        }
        return round($total,2);
    }
    public function getActiveCustomers(){
        $count = 0;
        foreach($this->customers as $customer){
            if($customer->hasActiveSubscription()){
                $count++;
            }
        }
        return $count;
    }
    public function getNonActiveCustomers(){
        $count = 0;
        foreach($this->customers as $customer){
            if($customer->hasActiveSubscription()===false && $customer->hasSubscription()){
                $count++;
            }
        }
        return $count;
    }
    private function getPaidCustomers(){
        $count = DB::table('transactions')->select('distinct customer_id')->where('status','=','Completed')->where('total','>','0')->where('coupon_id','=',$this->id)->count();
        return $count;
    }
    public function assignSearch($request){
        foreach(self::$searchableColumns as $property){
            $this->{$property} = $request->input($property);
        }
        if($request->exists('status')){
            if($request->input('status')=='all'){
                $this->statuses = ['Active', 'Disabled'];
            }else{
                $this->statuses = [$request->input('status')];
            }
        }else{
            $this->statuses = ['Active', 'Disabled'];
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public function generatePrivateTrialBefore($subscription){
        $this->customer_id = $subscription->customer_id;
        $this->code = self::TRIAL_BEFORE.'_'.$subscription->plan->service_id.'_'.$subscription->customer_id;
        $this->name = "Cupón 30";
        $this->renewal = 0;
        $this->type = "Private";
        $this->discount = 30;
        $this->save();
        $url = config('app.url').self::COUPON_URL.$this->code;
        Mail::to($subscription->customer->email)->send(new CouponTrialBefore($subscription->customer->first_name,$url));
    }
    public function generatePrivateTrialAfter($subscription){
        $this->customer_id = $subscription->customer_id;
        $this->code = self::TRIAL_AFTER.'_'.$subscription->plan->service_id.'_'.$subscription->customer_id;
        $this->name = "Cupón 30";
        $this->renewal = 0;
        $this->type = "Private";
        $this->discount = 30;
        $this->save();
        $url = config('app.url').self::COUPON_URL.$this->code;
        Mail::to($subscription->customer->email)->send(new CouponTrialAfter($subscription->customer->first_name,$url));
    }
    public static function scrape(){
        $coupons = self::whereType('Private')->get();
        $t1 = time();
        foreach( $coupons as $coupon){
            $t2 = strtotime( $coupon->created_at );
            $diff = $t1 - $t2;
            $hours = $diff / 3600;
            if($hours>48 && $coupon->status=="Active"){
                $coupon->status = "Disabled";
                $coupon->save();
            }
            if($hours>480 && $coupon->status=="Disabled"){
                //$coupon->status = "Disabled";
                //$coupon->save();
            }
        }
    }
    public function validate($customerId){
        if($this->status == 'Disabled') return false;
        if($this->type=='Public')return true;
        if($this->type=='Referral')return true;
        if($this->customer_id == $customerId) return true;
        return false;
    }
    public static function findCouponWithReferral($id,$customerId){
        if($id!=null){
            $coupon = Coupon::find($id);
            if($coupon && $coupon->status == "Active"){
                if($coupon->type == "Referral"){
                    $customer = Customer::find($coupon->customer_id);
                    if($customer && $customer->hasActiveSubscription())return $coupon;
                }else {
                    return $coupon;
                }
            }
        }
        return null;
    }
    public static function createRenewal($user){
        $service_id = 1;// for workouts
        $code = self::RENEWAL.'_'.$service_id.'_'.$user->customer->id;
        $coupon = Coupon::whereCode($code)->whereType('Private')->whereCustomerId($user->customer->id)->first();
        if($coupon === null){
            $coupon = new Coupon;
            $coupon->customer_id = $user->customer->id;
            $coupon->code = $code;
            $coupon->name = "Cupón 10";
            $coupon->renewal = 1;
            $coupon->type = "Private";
            $coupon->discount = 10;
        }else{
            $coupon->status = "Active";
        }
        $coupon->save();
        return $coupon;
    }
    public static function createFirstPay($user){
        $service_id = 1;// for workouts
        $code = self::FIRST_PAY.'_'.$service_id.'_'.$user->customer->id;
        $coupon = Coupon::whereCode($code)->whereType('Private')->whereCustomerId($user->customer->id)->first();
        if($coupon === null){
            $coupon = new Coupon;
            $coupon->customer_id = $user->customer->id;
            $coupon->code = $code;
            $coupon->name = "Checkout10";
            $coupon->renewal = 0;
            $coupon->type = "Private";
            $coupon->discount = 10;
        }else{
            $coupon->status = "Active";
        }
        $coupon->save();
        return $coupon;
    }
    public static function sendReport(){
        $coupons = Coupon::whereType('Public')->whereStatus('Active')->get();
        foreach($coupons as $coupon){
            if($coupon->mail!=null){
                $active = $coupon->getActiveCustomers();
                $nonActive = $coupon->getNonActiveCustomers();
                $fromDate = date('Y-m-d',strtotime('-7days'));
                $total = $coupon->getTotal(date('Y-m-d',strtotime('-7days')));
                $fromDate = date("M,d Y",strtotime($fromDate));
                Mail::to($coupon->mail)->send(new CouponReport($active,$nonActive,$total,$coupon->name,$fromDate));
            }
        }
    }
    public function getDiscountedAmount($amount,$partnerDiscount,$transaction=null){
        if ($this->renewal == 1) {
            if($this->form=='%'){
                if( $partnerDiscount && $this->discount < $partnerDiscount ) $amount = round($amount * (100 - $partnerDiscount)/100,2);
                else $amount = round($amount * (100 - $this->discount)/100,2); 
            }
            else {
                if( $partnerDiscount && $this->discount < $partnerDiscount * $amount / 100 ){
                    $amount = round($amount * (100 - $partnerDiscount)/100,2);
                }else $amount = round($amount - $this->discount,2);
                if($amount<0) $amount = 0;
            }
        } else {
            if ($transaction === null) {
                if($this->form=='%'){
                    if( $partnerDiscount && $this->discount < $partnerDiscount ) $amount = round($amount * (100 - $partnerDiscount)/100,2);
                    else $amount = round($amount * (100 - $this->discount)/100,2); 
                }
                else {
                    if( $partnerDiscount && $this->discount < $partnerDiscount * $amount / 100 ){
                        $amount = round($amount * (100 - $partnerDiscount)/100,2);
                    }else $amount = round($amount - $this->discount,2);
                    if($amount<0) $amount = 0;
                }
            }else{
                if( $partnerDiscount ) $amount = round($amount * (100 - $partnerDiscount)/100,2);        
            }
        }
        return $amount;
    }
}
