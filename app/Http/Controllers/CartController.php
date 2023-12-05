<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Cart;
use App\SubscriptionPlan;

/**
 * @group Cart
 *
 * APIs for managing  cart
 */

class CartController extends Controller
{
    /**
     * inside cart
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function inside(Request $request)
    {
        $cart = Cart::inside($request);
        return response()->json(array('status'=>'ok','cart'=>$cart));
    }
    /**
     * outside cart.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function outside(Request $request)
    {
        $user = $request->user('api');
        $cart = Cart::whereCustomerId($user->customer->id)->first();
        if($cart)$cart->outside();
        return response()->json(array('status'=>'ok','cart'=>$cart));
    }
    /**
     * show cart.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function show($id,Request $request){
        $cart = Cart::find($id);
        $user = $request->user('api');
        if($cart && $cart->customer_id == $user->customer->id){
            if(!$user->customer->hasSubscription()){
                $cart->frequency = 'monthly';
            }
            switch($cart->frequency){
                case 'monthly':
                    $frequency = 1;
                break;
                case 'quarterly':
                    $frequency = 3;
                break;
                case 'semiannual':
                    $frequency = 6;
                break;
                case 'yearly':
                    $frequency = 12;
                break;
            }   
            $cart->delete(); 
            if(!$user->customer->hasSubscription())$frequency = 1;
            return response()->json(['status'=>'ok','cart'=>$cart,'frequency'=>$frequency,'coupon'=>$cart->assignedCoupon()]);
        }
        return response()->json(array('status'=>'failed'),422);
    }
}