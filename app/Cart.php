<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Mail\CartAbandonedNew;
use App\Mail\CartAbandonedRenewal;
use Mail;

class Cart extends Model
{
    protected $table = 'cart';
    protected $fillable = ['customer_id','session_id'];
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
    public static function validateRules(){
        return array(
            'name'=>'required|max:255',
        );
    }
    public static function inside(Request $request){
        $frequency = $request->input('frequency');
        $user = $request->user('api');
        $token = $request->user('api')->token();
        $session = Session::whereToken($token->id)->first();
        if($session){
            $plan = SubscriptionPlan::whereServiceId(1)->whereType('Paid')->first();
            $cart = Cart::firstOrNew(['session_id'=>$session->id]);
            $cart->customer_id = $user->customer->id;
            $cart->frequency = $frequency;
            $cart->plan_id = $plan->id;
            $cart->out = 'no';
            $cart->save();
        }else $cart = null;
        return $cart;
    }
    public static function timeout($session){
        $cart = self::whereSessionId($session->id)->first();
        if($cart){
            $cart->outside();
        }
    }
    public function outside(){
        if($this->out =='no'){
            $this->out = time();
            $this->save();
        }
    }
    public static function scrape(){
        $items = Cart::all();
        $cartAbandonSetting = Setting::getCart();
        if($cartAbandonSetting['unit']=='h'){
            $diffTime = $cartAbandonSetting['time']*3600;
        }else{
            $diffTime = $cartAbandonSetting['time']*3600*24;
        }
        foreach($items as $item){
            $diff = time() - strtotime($item->updated_at->toString());
            if($item->mail == "no"){
                if($item->out == 'no'){
                    if($diff>env('SESSION_TIMEOUT')*60){
                        $item->out = time();
                        $item->save();
                    }
                }else{
                    if($diff>$diffTime){
                        $item->sendAbandonMail($cartAbandonSetting);
                        $item->mail = 'yes';
                        $item->save();
                    }
                }
            }
        }
    }
    private function sendAbandonMail($cartAbandonSetting){
        if($this->customer->hasSubscription()){
            $coupon = Coupon::find($cartAbandonSetting['renewal_coupon_id']);
            if($coupon){
                $url = env('APP_URL').'/#checkout?cart='.$this->id;
                Mail::to($this->customer->email)->send(new CartAbandonedRenewal($this->customer->first_name,intval($coupon->discount).$coupon->form,$url));
            }else{
                //print_r('emailRenewalSend');print_r($cartAbandonSetting['renewal_coupon_id']);
                //print_r("\n");
            }
        }else{
            $coupon = Coupon::find($cartAbandonSetting['new_coupon_id']);
            if($coupon){
                $url = env('APP_URL').'/#checkout?cart='.$this->id;
                Mail::to($this->customer->email)->send(new CartAbandonedNew($this->customer->first_name,intval($coupon->discount).$coupon->form,$url,$coupon->code,$coupon->name));
            }else{
                //print_r('emailNewSend');print_r($cartAbandonSetting['new_coupon_id']);//[new_coupon_id]
                //print_r("\n");
            }
        }
    }
    public function assignedCoupon(){
        $cartAbandonSetting = Setting::getCart();
        if($this->customer->hasSubscription()){
            $coupon = Coupon::find($cartAbandonSetting['renewal_coupon_id']);
        }else{
            $coupon = Coupon::find($cartAbandonSetting['new_coupon_id']);
        }
        return $coupon;
    }
}