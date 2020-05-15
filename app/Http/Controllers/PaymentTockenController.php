<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentTocken;
use Illuminate\Support\Facades\Validator;

class PaymentTockenController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->input('nmi'), PaymentTocken::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        $tocken = new PaymentTocken;
        $result = $tocken->assign($request);        
        if($result===true){
            $tocken->save();
            return response()->json(array('status'=>'ok','tocken'=>$tocken));
        }else{
            return response()->json(['status' => 'failed', 'errors'=>$result['error_message']],422);
        }
    }
    public function update($id,Request $request)
    {
        $validator = Validator::make($request->input('nmi'), PaymentTocken::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        $tocken = PaymentTocken::find($id);
        $result = $tocken->assign($request);        
        if($result===true){
            $tocken->save();
            return response()->json(array('status'=>'ok','tocken'=>$tocken));
        }else{
            return response()->json(['status' => 'failed', 'errors'=>$result['error_message']],422);
        }
    }
    public function show($id){
        $tocken = PaymentTocken::find($id);
        if($tocken->mail==null){
            $tocken->mail = "";
        }
        return response()->json($tocken);
    }
    public function destroy($id){
        $tocken = PaymentTocken::find($id);
        if($tocken){
            $customerId = $tocken->customer_id;
            $tocken = $tocken->tocken;
            $destroy=PaymentTocken::destroy($id);            
        }
        if ($destroy){
            PaymentTocken::changeSubscription($customerId,$tocken);
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
    }
    public function index(Request $request){
        $tocken = new PaymentTocken;
        $tocken->assignSearch($request);
        return response()->json(['items'=>$tocken->search()]);
    }
}
