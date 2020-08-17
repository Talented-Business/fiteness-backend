<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Redirect;
use App\Customer;
use App\Coupon;
use App\User;
use App\Weight;
use App\Height;
use App\Condition;
use App\Shortcode;
use App\PaymentTocken;
use App\PaymentSubscription;
use App\Transaction;
use App\Setting;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Customer::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        $customer = new Customer;
        $response = $customer->assign($request);
        if($response===true){
            $customer->save();
            return response()->json(array('status'=>'ok','customer'=>$customer));
        }else{
            return response()->json(array('status'=>'failed','errors'=>$response));
        }
    }
    public function update($id,Request $request)
    {
        $validator = Validator::make($request->all(), Customer::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        $customer = Customer::find($id);
        $response = $customer->assign($request);
        if($response === true){
            $user->customer->increaseRecord('edit_count');
            $customer->save();
            return response()->json(array('status'=>'ok','customer'=>$customer));
        }else{
            return response()->json(array('status'=>'failed','errors'=>$response));
        }
    }
    public function show($id,Request $request){
        $user = $request->user('api');
        if($user->can('customers')){
            $customer = Customer::find($id);
            $customer->extends();
            //$customer['created_date'] = date("F d Y H:i",strtotime($customer->created_at));
            return response()->json($customer);
        }else{
            return response()->json(['status'=>'failed'],403);
        }    
    }
    public function index(Request $request){
        $user = $request->user('api');
        if($user->can('customers')){
            $customer = new Customer;
            $customer->assignSearch($request);
            $result = $customer->search();
            return response()->json($result);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function disable($id,Request $request){
        $user = $request->user('api');
        if($user->can('customers')){
            DB::beginTransaction();
            try{
                $customer = Customer::find($id);
                $customer->user->active = 0;
                $customer->user->save();
                DB::commit();
                $status = "ok";
            }  catch (\Exception $e) {
                DB::rollback();
                $status = "failed";
            }
            return response()->json(['status'=>$status]);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function restore($id,Request $request){
        $user = $request->user('api');
        if($user->can('customers')){
            DB::beginTransaction();
            try{
                $customer = Customer::find($id);
                $customer->user->active = 1;
                $customer->user->save();
                DB::commit();
                $status = "ok";
            }  catch (\Exception $e) {
                DB::rollback();
                $status = "failed";
            }
            return response()->json(['status'=>$status]);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function weights(Request $request){
        return $this->getWeights($request);
    }
    private function getWeights(Request $request){
        $user = $request->user('api');
        if($user&&$user->customer){
            $weights = $user->customer->latestWeights;
            if(isset($weights[0])){
                if($weights[0]->value != $user->customer->current_weight){
                    $user->customer->current_weight = $weights[0]->size;
                    $user->customer->current_weight_unit = $weights[0]->unit;
                    $user->customer->save();
                }
            }
            $heightValue = Height::convert($user->customer->current_height,$user->customer->current_height_unit)/100;
            foreach($weights as $index=>$weight){
                $date = date("d/m/Y",strtotime($weight->created_at));
                $weights[$index]['date'] = $date;
                $weights[$index]['imc'] = round($weight->value/$heightValue/$heightValue);
            }
            return $weights;
        }
        return [];
    }
    public function deleteWeight(Request $request){
        $user = $request->user('api');
        $id = $request->input('id');
        $weight = Weight::find($id);
        $weight->delete();
        $user->customer->increaseRecord('edit_count');
        return $this->getWeights($request);
    }
    public function updateWeight(Request $request){
        $user = $request->user('api');
        $unit = $user->customer->current_weight_unit;
        $id = $request->input('id');
        $weight = Weight::find($id);
        $weight->size=$request->input('size');
        $weight->unit=$unit;
        $weight->value = Weight::convert($weight->size, $unit);
        $weight->created_at=$request->input('date').' 00:00:00';
        $weight->save();
        $user->customer->increaseRecord('edit_count');
        return $this->getWeights($request);
    }
    public function storeWeight(Request $request){
        $user = $request->user('api');
        $unit = $user->customer->current_weight_unit;
        $weight = new Weight;
        $weight->customer_id = $user->customer->id;
        $weight->size=$request->input('size');
        $weight->unit=$unit;
        $weight->value = Weight::convert($weight->size, $unit);
        $weight->created_at=$request->input('date').' 00:00:00';
        $weight->save();
        $user->customer->increaseRecord('edit_count');
        return $this->getWeights($request);
    }
    public function conditions(){
        $conditions = Condition::all();
        foreach($conditions as $index=>$condition){
            $conditions[$index]->summury = Shortcode::replace($conditions[$index]->summury);
        }
        return response()->json($conditions);
    }
    public function nextCondition(Request $request){
        $user = $request->user('api');
        $count = count(Condition::all());
        if($user->customer->current_condition<$count){
            $user->customer->current_condition = $user->customer->current_condition + 1;
            $user->customer->save();
        }
        $user->customer->increaseRecord('edit_count');
        return response()->json(['condition'=>$user->customer->current_condition]);
    }
    public function previousCondition(Request $request){
        $user = $request->user('api');
        if($user->customer->current_condition>0){
            $user->customer->current_condition = $user->customer->current_condition - 1;
            $user->customer->save();
        }
        $user->customer->increaseRecord('edit_count');
        return response()->json(['condition'=>$user->customer->current_condition]);
    }
    public function changeCondition(Request $request){
        $user = $request->user('api');
        $condition = $request->input('condition');
        $count = count(Condition::all());
        if($condition<$count){
            $user->customer->current_condition = $condition;
            $user->customer->save();
        }
        $user->customer->increaseRecord('edit_count');
        return response()->json(['condition'=>$user->customer->current_condition]);
    }
    public function changeObjective(Request $request){
        $user = $request->user('api');
        $objective = $request->input('goal');
        $user->customer->objective = $objective;
        $user->customer->save();
        $user->customer->increaseRecord('edit_count');
        return response()->json(['condition'=>$user->customer->objective]);
    }
    public function changeWeights(Request $request){
        $user = $request->user('api');
        $weights = $request->input('weights');
        $user->customer->weights = $weights;
        $user->customer->save();
        return response()->json(['weights'=>$user->customer->weights]);
    }
    public function recentWorkouts(Request $request){
        $user = $request->user('api');
        $workouts = $user->customer->recentWorkouts();
        return response()->json(['workouts'=>$workouts,'profile'=>$user->customer->findMedal()]);
    }
    public function export(Request $request)
    {
        $user = $request->user('api');
        if($user->can('customers')){
            $customer = new Customer;
            $customer->assignSearch($request);
            $customers = $customer->searchAll();
            $export = Customer::export($customers);
            return Excel::download($export,'customers.xlsx');   
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function activity(Request $request){
        $column = $request->input('column');
        $user = $request->user('api');
        if($user && $user->customer)$user->customer->increaseRecord($column);
        return response()->json(['status'=>'ok']);
    }
    public function link(Request $request){
        $exist = false;
        if($request->exists('shortcode_id')){
            $shortcode = Shortcode::find($request->input('shortcode_id'));
            if($shortcode){
                $exist = true;
                $redirectLink = $shortcode->link;
            }
        }
        if(!$exist)$redirectLink = 'https://youtu.be/qcQJi0wb2Dg';
        if($request->exists('customer_id')){
            $customer = Customer::find($request->input('customer_id'));
            if($customer){
                $customer->increaseRecord('email_count');
            }
        }
        return Redirect::away($redirectLink);
    }
    public function triggerWorkout(Request $request){
        $user = $request->user('api');
        if($user->customer){
            $user->customer->active_email=!$user->customer->active_email;
            $user->customer->save();
            return response()->json(['status'=>$user->customer->active_email]);
        }
    }
    public function triggerNotifiable(Request $request){
        $user = $request->user('api');
        if($user->customer){
            $user->customer->notifiable=!$user->customer->notifiable;
            $user->customer->save();
            return response()->json(['status'=>$user->customer->notifiable]);
        }
        return response()->json(['status'=>'failed'],403);
    }
    public function showReferralCoupon(Request $request){
        $user = $request->user('api');
        if($user->customer){
            $referralCoupon = $user->customer->findReferralCoupon();
            if($referralCoupon == null){
                $referralCoupon = new Coupon;
                $referralCoupon->type = "Referral";
                $referralCoupon->customer_id = $user->customer->id;
                $referralCoupon->name = "Referral";
                $referralCoupon->code = "r".$user->customer->id;
                $referralCoupon->discount = Setting::getReferralDiscount();
                $referralCoupon->renewal = 1;
                $referralCoupon->save();
            }
            return response()->json(['referralCoupon'=>$referralCoupon]);
        }
        return response()->json(['status'=>'failed'],403);
    }
    public function referral(Request $request){
        $user = $request->user('api');
        if($user->customer && $user->customer->hasActiveSubscription()){
            $referralUrl = $user->customer->findReferralUrl();
            $discount = Setting::getReferralDiscount();
            return response()->json(['referralUrl'=>$referralUrl,'discount'=>$discount]);
        }
        return response()->json(['status'=>'failed']);
    }
    public function partners(Request $request){
        $user = $request->user('api');
        if($user->customer && $user->customer->hasActiveSubscription()){
            $partners = $user->customer->findPartners();
            return response()->json(['partners'=>$partners]);
        }
        return response()->json(['status'=>'failed']);
    }
    public function ccard(Request $request){
        $user = $request->user('api');
        if($user->customer){
            $paymentToken = PaymentTocken::whereCustomerId($user->customer->id)->first();
            if($paymentToken)return response()->json(['number'=>$paymentToken->last4]);
        }
        return response()->json(['number'=>false]);
    }
}