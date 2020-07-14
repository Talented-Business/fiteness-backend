<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;
use App\User;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user('api');
        if($user->can('coupons')){
            $validator = Validator::make($request->all(), Coupon::validateRules());
            if ($validator->fails()) {
                return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
            }
            $coupon = new Coupon;
            $coupon->assign($request);        
            $coupon->save();
            $coupon->renewal = (int)$coupon->renewal;
            return response()->json(array('status'=>'ok','coupon'=>$coupon));
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function update($id,Request $request)
    {
        $user = $request->user('api');
        if($user->can('coupons')){
            $validator = Validator::make($request->all(), Coupon::validateRules($id));
            if ($validator->fails()) {
                return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
            }
            $coupon = Coupon::find($id);
            $coupon->assign($request);
            $coupon->save();
            $coupon->renewal = (int)$coupon->renewal;
            return response()->json(array('status'=>'ok','coupon'=>$coupon));
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function show($id){
        $user = $request->user('api');
        if($user->can('coupons')){
            $coupon = Coupon::find($id);
            if($coupon->mail==null){
                $coupon->mail = "";
            }
            return response()->json($coupon);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function destroy($id){
        $user = $request->user('api');
        if($user->can('coupons')){
            $coupon = Coupon::find($id);
            if($coupon){
                $destroy=Coupon::destroy($id);
            }
            if ($destroy){
                $data=[
                    'status'=>'1',
                    'msg'=>'success'
                ];
            }else{
                $data=[
                    'status'=>'0',
                    'msg'=>'fail'
                ];
            }        
            return response()->json($data);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function index(Request $request){
        $user = $request->user('api');
        if($user->can('coupons')){
            $coupon = new Coupon;
            $coupon->assignSearch($request);
            return response()->json($coupon->search());
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function disable($id,Request $request){
        $user = $request->user('api');
        if($user->can('coupons')){
            $coupon = Coupon::find($id);
            $coupon->status = "Disabled";
            $coupon->save();
            $coupon->renewal = (int)$coupon->renewal;
            return response()->json($coupon);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function active($id,Request $request){
        $user = $request->user('api');
        if($user->can('coupons')){
            $coupon = Coupon::find($id);
            $coupon->status = "Active";
            $coupon->save();
            $coupon->renewal = (int)$coupon->renewal;
            return response()->json($coupon);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function check(Request $request){
        $inputs = $request->input('voucher');
        $code = $inputs['token'];
        $coupon = Coupon::whereCode($code)->whereType('Public')->whereStatus('Active')->first();
        if($coupon===null){
            $user = $request->user('api');
            $coupon = Coupon::whereCustomerId($user->customer->id)->whereType('Private')->whereCode($code)->whereStatus('Active')->first();    
        }
        if($coupon){
            $userId = $inputs['user_id'];
            $user = User::find($userId);
            $coupon['token'] = $code;
            $coupon->renewal = (int)$coupon->renewal;
            if($coupon->renewal === 0 && $user->customer->hasSubscription()){
                return response()->json(['errors' => ['token'=>[['error'=>'invalid']]]],422);    
            }
            return response()->json(['voucher'=>$coupon]);
        }else{
            return response()->json(['errors' => ['token'=>[['error'=>'invalid']]]],422);
        }
    }
    public function generateFirstPay(Request $request){
        $user = $request->user('api');
        $coupon = Coupon::createFirstPay($user);
        if($coupon){
            $coupon['token'] = $coupon->code;
            $coupon->renewal = (int)$coupon->renewal;
            return response()->json(['voucher'=>$coupon]);
        }else{
            return response()->json(['errors' => ['token'=>[['error'=>'invalid']]]],422);
        }
    }
    public function private(Request $request){
        $inputs = $request->input('voucher');
        $code = $inputs['code'];
        $user = $request->user('api');
        $coupon = Coupon::whereCustomerId($user->customer->id)->whereType('Private')->whereCode($code)->whereStatus('Active')->first();
        if($coupon){
            $coupon['token'] = $code;
            $coupon->renewal = (int)$coupon->renewal;
            return response()->json(['voucher'=>$coupon]);
        }else{
            return response()->json(['errors' => ['token'=>[['error'=>'invalid']]]],422);
        }
    }
    public function public(Request $request){
        $inputs = $request->input('voucher');
        $code = $inputs['code'];
        $coupon = Coupon::whereType('Public')->whereCode($code)->whereStatus('Active')->first();
        if($coupon){
            $coupon['token'] = $code;
            $coupon->renewal = (int)$coupon->renewal;
            return response()->json(['voucher'=>$coupon]);
        }else{
            return response()->json(['errors' => ['token'=>[['error'=>'invalid']]]],422);
        }
    }
    public function publicWithUser(Request $request){
        $inputs = $request->input('voucher');
        $couponId = $inputs['id'];
        $coupon = Coupon::find($couponId);
        if($coupon && $coupon->type=='Public' && $coupon->status == 'Active'){
            $coupon['token'] = $coupon->code;
            $coupon->renewal = (int)$coupon->renewal;
            return response()->json(['voucher'=>$coupon]);
        }else{
            return response()->json(['errors' => ['token'=>[['error'=>'invalid']]]],422);
        }
    }
    public function createRenewal(Request $request){
        $user = $request->user('api');
        $coupon = Coupon::createRenewal($user);
        if($coupon){
            $coupon['token'] = $coupon->code;
            $coupon->renewal = (int)$coupon->renewal;
            return response()->json(['voucher'=>$coupon]);
        }else{
            return response()->json(['errors' => ['token'=>[['error'=>'invalid']]]],422);
        }
    }
}
