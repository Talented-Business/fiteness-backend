<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Customer;
use App\Mail\MailQueue;
use Mail;

/**
 * @group Following    on social part
 *
 * APIs for managing  followings
 */

class FollowController extends Controller
{
    /**
     * get pending follow requests of authenticated customer.
     * 
     * @group Follow
     * This endpoint returns followers with pending following status
     * @authenticated
     * @response {
     *      "requests":[
     *          {customer}
     *      ]
     * }
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $requests = DB::table("follows")->select("*")->where('customer_id',$user->customer->id)->whereIn('status',['pending'])->get();
        foreach( $requests as $item ){
            $item->customer = Customer::find($item->follower_id);
            $item->customer->getAvatar();
        }
        return response()->json(['requests'=>$requests]);
    }
    /**
     * create follow request.
     * 
     * @group Follow
     * This endpoint.
     * @bodyParam customer_id integer required
     * @authenticated
     * @response {
     *  "status":"accepted", // or pending when customer type is private
     *  "customer":{customer}
     * }
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $customerId = $request->customer_id;
        $follow = DB::table("follows")->select("*")->where('customer_id',$customerId)->whereFollowerId($user->customer->id)->first();
        if($follow == null){
            $customer = Customer::find($customerId);
            if($customer->profile == 'private'){
                DB::table('follows')->insert([
                    'customer_id' => $customerId,
                    'follower_id' => $user->customer->id,
                    'status' =>'pending'
                ]);
                $status = 'pending';
            }else{
                DB::table('follows')->insert([
                    'customer_id' => $customerId,
                    'follower_id' => $user->customer->id,
                    'status' =>'accepted'
                ]);
                $status = 'accepted';
            }
            if(!$customer->hasActiveSubscription()){
                $data = ['sender_first_name'=>$user->customer->first_name,'receiver_first_name'=>$customer->first_name,'email'=>$customer->email,'view_file'=>'emails.customers.following','subject'=>$customer->first_name.' y otras personas te empezaron a seguir en Fitemos'];
                Mail::to($customer->email, $customer->first_name.' '.$customer->last_name)->queue(new MailQueue($data));
            }
            \App\Models\Notification::follow($customer->id, $user->customer);
            $customer = $this->findCustomer($customerId, $user);
            return response()->json(array('status'=>$status,'customer'=>$customer));
        }
        return response()->json(array('status'=>'ok'));
    }
    /**
     * accept follow request.
     * 
     * @group Follow
     * This endpoint.
     * @authenticated
     * @urlParam id integer required //customer id
     * @response {
     *  "status":"accepted"
     *  "customer":{customer}
     * }
     */
    public function accept($id,Request $request)
    {
        $user = $request->user();
        $follow = DB::table("follows")->select("*")->where('id',$id)->first();
        if($follow->status == 'pending' && $follow->customer_id == $user->customer->id){
            $customerId = $follow->customer_id;
            DB::table("follows")->select("*")->where('id',$id)->update(['status'=>'accepted']);
            $customer = $this->findCustomer($customerId, $user);
            return response()->json(array('status'=>'accepted','customer'=>$customer));
        }
        return response()->json(array('status'=>'failed'),403);    
    }
    /**
     * reject follow request.
     * 
     * @group Follow
     * This endpoint.
     * @authenticated
     * @urlParam id integer required //customer id
     * @response {
     *  "status":"rejected"
     *  "customer":{customer}
     * }
     */
    public function reject($id,Request $request)
    {
        $user = $request->user();
        $follow = DB::table("follows")->select("*")->where('id',$id)->first();
        if($follow->status == 'pending' && $follow->customer_id == $user->customer->id){
            $customerId = $follow->customer_id;
            DB::table("follows")->select("*")->where('id',$id)->update(['status'=>'rejected']);
            $customer = $this->findCustomer($customerId, $user);
            return response()->json(array('status'=>'rejected','customer'=>$customer));
        }
        return response()->json(array('status'=>'failed'),403);    
    }
    /**
     * unfollow a customer.
     * 
     * @group Follow
     * This endpoint.
     * @authenticated
     * @urlParam customer_id integer required //customer id
     * @response {
     *  "status":"ok"
     *  "customer":{customer}
     * }
     */
    public function unfollow(Request $request){
        $user = $request->user();
        $customerId = $request->customer_id;
        DB::table("follows")->select("*")->where('customer_id',$customerId)->whereFollowerId($user->customer->id)->delete();
        $customer = $this->findCustomer($customerId, $user);
        return response()->json(array('status'=>'ok','customer'=>$customer));
    }
    /**
     * block a customer.
     * 
     * @group Block and mute
     * This endpoint.
     * @authenticated
     * @bodyParam customer_id integer required
     * @authenticated
     * @response {
     *  "status":"ok",
     *  "customer":{customer}
     * }
     */
    public function block(Request $request){
        $user = $request->user();
        $customerId = $request->customer_id;
        DB::table('customers_relations')->updateOrInsert(
            ['status' =>'blocked'],
            ['customer_id' => $customerId,
            'follower_id' => $user->customer->id]
        );
        $customer = $this->findCustomer($customerId, $user);
        return response()->json(array('status'=>'ok','customer'=>$customer));
    }
    /**
     * unblock a customer.
     * 
     * @group Block and mute
     * This endpoint.
     * @authenticated
     * @bodyParam customer_id integer required
     * @authenticated
     * @response {
     *  "status":"ok",
     *  "customer":{customer}
     * }
     */
    public function unblock(Request $request){
        $user = $request->user();
        $customerId = $request->customer_id;
        DB::table("customers_relations")->select("*")->where('customer_id',$customerId)->whereFollowerId($user->customer->id)->delete();
        $customer = $this->findCustomer($customerId, $user);
        return response()->json(array('status'=>'ok','customer'=>$customer));
    }
    /**
     * mute a customer.
     * 
     * @group Block and mute
     * This endpoint.
     * @authenticated
     * @bodyParam customer_id integer required
     * @authenticated
     * @response {
     *  "status":"ok",
     *  "customer":{customer}
     * }
     */
    public function mute(Request $request){
        $user = $request->user();
        $customerId = $request->customer_id;
        $relation = DB::table("customers_relations")->select("*")->where('customer_id',$customerId)->whereFollowerId($user->customer->id)->first();
        // if($relation && $relation->status == 'blocked'){
        //     return response()->json(array('status'=>'failed'),421);    
        // }
        DB::table('customers_relations')->updateOrInsert(
            ['status' =>'muted'],
            ['customer_id' => $customerId,
            'follower_id' => $user->customer->id]
        );        
        $customer = $this->findCustomer($customerId, $user);
        return response()->json(array('status'=>'ok','customer'=>$customer));
    }
    /**
     * unmute a customer.
     * 
     * @group Block and mute
     * This endpoint.
     * @authenticated
     * @bodyParam customer_id integer required
     * @authenticated
     * @response {
     *  "status":"ok",
     *  "customer":{customer}
     * }
     */
    public function unmute(Request $request){
        $user = $request->user();
        $customerId = $request->customer_id;
        $relation = DB::table("customers_relations")->select("*")->where('customer_id',$customerId)->whereFollowerId($user->customer->id)->first();
        // if($relation && $relation->status == 'blocked'){
        //     return response()->json(array('status'=>'failed'),421);    
        // }
        DB::table("customers_relations")->select("*")->where('customer_id',$customerId)->whereFollowerId($user->customer->id)->delete();
        $customer = $this->findCustomer($customerId, $user);
        return response()->json(array('status'=>'ok','customer'=>$customer));
    }
    private function findCustomer($customerId, $user){
        $customer = Customer::find($customerId);
        $customer->type="customer";
        $customer->getSocialDetails($user->customer->id);
        $customer['medals'] = $customer->findMedal();
        if($customer->user->chat_id)$customer->chat_id = $customer->user->chat_id;
        $user->customer->touch();
        return $customer;
    }
    /**
     * search followings or followers by 20 per page.
     * 
     * This endpoint.
     * @authenticated
     * @bodyParam customer_id integer required
     * @bodyParam type string required followings or follower
     * @bodyParam page_number integer
     * @response {
     *      "follows":[{customer}],
     *      "next":5,
     * }
     */
    public function customerIndex(Request $request){
        $user = $request->user();
        $customer = Customer::find($request->customer_id);
        if($request->exists('page_number')){
            $pageNumber = $request->page_number; 
        }else{
            $pageNumber = 0; 
        }
        $pageSize = 20;
        if($request->type=='followings'){
            /** followings */
            $where = DB::table("follows")->select("*")->where('follower_id',$customer->id);
            if($customer->id != $user->customer->id)$where->whereIn('status',['accepted']);
            $where->orderBy('id')->offset($pageNumber * $pageSize)->limit($pageSize)->get();
            $follows = $where->get();
            $where = DB::table("follows")->select("*")->where('follower_id',$customer->id);
            if($customer->id != $user->customer->id)$where->whereIn('status',['accepted']);
            $where->orderBy('id')->offset(($pageNumber+1) * $pageSize)->limit($pageSize);
            $next = $where->get()->count();
        }else{
            /** followers */
            $where = DB::table("follows")->select("*")->where('customer_id',$customer->id);
            if($customer->id != $user->customer->id)$where->whereIn('status',['accepted']);
            $where->orderBy('id')->offset($pageNumber * $pageSize)->limit($pageSize)->get();
            $follows = $where->get();
            $where = DB::table("follows")->select("*")->where('customer_id',$customer->id);
            if($customer->id != $user->customer->id)$where->whereIn('status',['accepted']);
            $where->orderBy('id')->offset(($pageNumber+1) * $pageSize)->limit($pageSize);
            $next = $where->get()->count();
        }
        foreach( $follows as $follow){
            if($request->type=='followings'){
                $follow->customer = Customer::find($follow->customer_id);
                $follow->customer->getAvatar();
            }else{
                $follow->follower = Customer::find($follow->follower_id);
                $follow->follower->getAvatar();
            }
        }
        return response()->json(['follows'=>$follows,'next'=>$next]);
    }

}