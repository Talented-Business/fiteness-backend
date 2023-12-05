<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Customer;
use App\User;
/**
 * @group ProfileManager   
 *
 * APIs for managing  likes for post and comment on social part
 */

class ProfileManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:customers']);
    }
    /**
     * create a profileManager.
     * 
     * This endpoint.
     * @authenticated
     * @bodyParam activity_id integer required
     * @response {
     * }
     */
    public function store(Request $request){
        if(!$request->exists('customer_id')){
            return response()->json(array('status'=>'failed'),422);
        }
        $customer = Customer::find($request->customer_id);
        if($customer->user->hasRole('profileManager')){
            return response()->json(array('status'=>'already exists'),421);
        }
        $customer->user->assignRole('profileManager');
        Cache::forget('profileManagerIds');
        return response()->json([
            'profileManager'=>$customer]
            );        
        
    }
    /**
     * delete a profileManager.
     * 
     * This endpoint.
     * @authenticated
     * @urlParam id integer required
     * @response {
     * }
     */
    public function destroy($id,Request $request){
        Cache::forget('profileManagerIds');
        $customer = Customer::find($id);
        if($customer->user->hasRole('profileManager')){
            $customer->user->removeRole('profileManager');
            return response()->json([
                'status'=>'1'
                ]);
        }
        $data=[
            'status'=>'0',
            'msg'=>'fail'
        ];
        return response()->json($data,403);
    }
    /**
     * get customers with profileManager Role.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * [{customer}]
     * }
     */
    public function index(Request $request){
        Cache::forget('profileManagerIds');
        $users = User::whereHas("roles", function($q){ $q->where("name", "profileManager"); })->get();
        $customers = [];
        $profileManagerIds = [];
        foreach($users as $user){
            if($user->customer){
                $customers[] = $user->customer;
                $profileManagerIds[] = $user->customer->id;
            }
        }
        if(count($profileManagerIds)>0)Cache::forever('profileManagerIds', $profileManagerIds);
        return response()->json($customers);
    }

}