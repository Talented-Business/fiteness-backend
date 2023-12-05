<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Mail;
use App\BankTransferRequest;
use App\SubscriptionPlan;
use App\Subscription;
use App\Coupon;
use App\Payment\Bank;
use App\Mail\BankRequest;
/**
 * @group Bank Transfer
 *
 * APIs for managing  bank transfer
 */

class BankTransferController extends Controller
{
    /**
     * checkout bank transfer.
     * 
     * This endpoint.
     * plan_id = 2 for service_id = 1
     * @authenticated
     * @response {
     * }
     */

    public function checkout(Request $request)
    {
        $user = $request->user('api');
        $validator = Validator::make($request->all(), BankTransferRequest::validateRules());
        if ($validator->fails()) {
            return response()->json(['status'=>'failed','errors'=>$validator->errors()],422);
        }
        if(!$request->exists('service_id'))$request->service_id = 1;
        $plan = SubscriptionPlan::where('service_id', '=', $request->input('service_id'))->where('type', '=', 'Paid')->first();
        $bankFee = $plan->bank_fee;
        $coupon = null;
        if($request->coupon_id)$coupon = Coupon::find($request->coupon_id);
        $amount = $plan->{"month_$request->frequency"};
        $amount = $user->customer->getPayAmount($amount,$coupon);
        $total = $bankFee + $amount;
        $model = BankTransferRequest::whereStatus('Pending')->whereCustomerId($user->customer->id)->first();
        if($model == null){
            $model = new BankTransferRequest;
        }
        $frequency = (new Subscription)->convertFrequencyString($request->frequency);
        $model->fill(['customer_id'=>$user->customer->id,'plan_id'=>2,'coupon_id'=>$request->coupon_id?$request->coupon_id:null,'total'=>$total,'frequency'=>$frequency]);
        $model->save();
        $duration = $request->frequency;
        if($user->customer->isFirstPayment($request->service_id))$duration++;
        Mail::to($user->email)->send(new BankRequest($user->customer,$duration,$amount, $bankFee));
        return response()->json(['status'=>'ok','request'=>$model]);
    }
    /**
     * show a bank transfer.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function show($id){
        $bankTransferRequest = BankTransferRequest::find($id);
        return response()->json($bankTransferRequest);
    }
    /**
     * search bank transfers.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function index(Request $request){
        $bankTransferRequest = new BankTransferRequest;
        $bankTransferRequest->assignSearch($request);
        return response()->json($bankTransferRequest->search());
    }
    /**
     * approve a bank transfer.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function approve($id,Request $request){
        $user = $request->user('api');
        if($user->can('transactions')){
            DB::beginTransaction();
            try{
                $bankTransferRequest = BankTransferRequest::find($id);
                $bankTransferRequest->status = 'Completed';
                $transaction = $bankTransferRequest->createTransaction();
                if($transaction){
                    $bankTransferRequest->transaction_id = $transaction->id; 
                    $bankTransferRequest->done_date = date("Y-m-d H:i:s");
                    $bankTransferRequest->save();
                    $bankTransferRequest->refresh();
                    $bankTransferRequest->createOrUpdateSuscription();
                    DB::commit();
                    return response()->json(['status'=>'ok','request'=>$bankTransferRequest]);
                }
            }  catch (\Exception $e) {
                DB::rollback();
                print_r($e->getMessage());
            }

            return response()->json(['status'=>'failed','message'=>'failed'],403);
        }
        return response()->json(['status'=>'failed','message'=>'forbidden action'],404);
    }
    /**
     * reject a bank transfer.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function reject($id,Request $request){
        $user = $request->user('api');
        if($user->can('transactions')){
            $bankTransferRequest = BankTransferRequest::find($id);
            $bankTransferRequest->status = 'Declined';
            $bankTransferRequest->save();
            return response()->json(['status'=>'ok','request'=>$bankTransferRequest]);
        }
        return response()->json(['status'=>'failed','message'=>'forbidden action'],404);
    }
    /**
     * restore a bank transfer.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function restore($id,Request $request){
        $user = $request->user('api');
        if($user->can('transactions')){
            $bankTransferRequest = BankTransferRequest::find($id);
            $bankTransferRequest->status = 'Pending';
            $bankTransferRequest->save();
            return response()->json(['status'=>'ok','request'=>$bankTransferRequest]);
        }
        return response()->json(['status'=>'failed','message'=>'forbidden action'],404);
    }
}
