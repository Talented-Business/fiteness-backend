<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Redirect;
use App\Customer;
use App\Coupon;
use App\User;
use App\Weight;
use App\Height;
use App\Condition;
use App\Shortcode;
use App\PaymentTocken;
use App\Setting;
use App\CustomerShortcode;


/**
 * @group Customer
 *
 * APIs for managing  customer
 */

class CustomerController extends Controller
{
    /**
     * create a customer.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
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
    /**
     * update a customer.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function update($id,Request $request)
    {
        $validator = Validator::make($request->all(), Customer::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        $customer = Customer::find($id);
        $response = $customer->assign($request);
        if($response === true){
            $customer->increaseRecord('edit_count');
            \App\Jobs\Activity::dispatch($customer);
            $customer->save();
            return response()->json(array('status'=>'ok','customer'=>$customer));
        }else{
            return response()->json(array('status'=>'failed','errors'=>$response));
        }
    }
    /**
     * show a customer.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function show($id,Request $request){
        $user = $request->user('api');
        if($user->can('customers')|| $user->can('social')){
            $customer = Customer::find($id);
            $customer->extends();
            //$customer['created_date'] = date("F d Y H:i",strtotime($customer->created_at));
            return response()->json($customer);
        }else{
            return response()->json(['status'=>'failed'],403);
        }    
    }
    /**
     * search customers.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function index(Request $request){
        $user = $request->user('api');
        if($user->can('customers') || $user->can('social')){
            $customer = new Customer;
            $customer->assignSearch($request);
            $result = $customer->search();
            return response()->json($result);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * disable a customer.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
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
    /**
     * restore a customer.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
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
    /**
     * get weights.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function weights(Request $request){
        return $this->getWeights($request);
    }
    private function getWeights(Request $request){
        $user = $request->user('api');
        if($user&&$user->customer){
            $weights = $user->customer->latestWeights;
            \App\Jobs\Activity::dispatch($user->customer);
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
    /**
     * delete weight.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function deleteWeight(Request $request){
        $user = $request->user('api');
        $id = $request->input('id');
        $weight = Weight::find($id);
        $weight->delete();
        if($user->customer)$user->customer->increaseRecord('edit_count');
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return $this->getWeights($request);
    }
    /**
     * update weight.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
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
        if($user->customer)$user->customer->increaseRecord('edit_count');
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return $this->getWeights($request);
    }
    /**
     * create weight.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
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
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return $this->getWeights($request);
    }
    /**
     * get all conditions.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function conditions(){
        $conditions = Condition::all();
        foreach($conditions as $index=>$condition){
            $conditions[$index]->summury = Shortcode::replace($conditions[$index]->summury);
        }
        return response()->json($conditions);
    }
    /**
     * next condition.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function nextCondition(Request $request){
        $user = $request->user('api');
        $count = count(Condition::all());
        if($user->customer->current_condition<$count){
            $user->customer->current_condition = $user->customer->current_condition + 1;
            $user->customer->save();
        }
        $user->customer->increaseRecord('edit_count');
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return response()->json(['condition'=>$user->customer->current_condition]);
    }
    /**
     * previous condition.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function previousCondition(Request $request){
        $user = $request->user('api');
        if($user->customer->current_condition>0){
            $user->customer->current_condition = $user->customer->current_condition - 1;
            $user->customer->save();
        }
        $user->customer->increaseRecord('edit_count');
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return response()->json(['condition'=>$user->customer->current_condition]);
    }
    /**
     * change condition.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function changeCondition(Request $request){
        $user = $request->user('api');
        $condition = $request->input('condition');
        $count = count(Condition::all());
        if($condition<$count){
            $user->customer->current_condition = $condition;
            $user->customer->save();
        }
        $user->customer->increaseRecord('edit_count');
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return response()->json(['condition'=>$user->customer->current_condition]);
    }
    /**
     * change object.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function changeObjective(Request $request){
        $user = $request->user('api');
        $objective = $request->input('goal');
        $user->customer->objective = $objective;
        $user->customer->save();
        $user->customer->increaseRecord('edit_count');
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return response()->json(['condition'=>$user->customer->objective]);
    }
    /**
     * change weights.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function changeWeights(Request $request){
        $user = $request->user('api');
        $weights = $request->input('weights');
        $user->customer->weights = $weights;
        if($user->customer->weights === 'sin pesas'){
            $user->customer->dumbells_weight = null;
        }
        $user->customer->save();
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return response()->json(['weights'=>$user->customer->weights]);
    }
    /**
     * recent workouts.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function recentWorkouts(Request $request){
        $user = $request->user('api');
        if($user->customer){
            $workouts = $user->customer->recentWorkouts();
            if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
            return response()->json(['workouts'=>$workouts,'profile'=>$user->customer->findMedal()]);
        }else{
            return response()->json(array('status'=>'forbidden'), 403);            
        }
    }
    public function exportReady(Request $request)
    {
        $user = $request->user('api');
        if($user->can('customers')){
            if($request->exists('uid')){
                $status = Cache::get('export-'.$request->uid);
                if($status === 'start'){
                    return response()->json(['uid'=>$request->uid, 'status'=>'start']);
                } else if($status === 'completed') {
                    return response()->json(['uid'=>$request->uid, 'status'=>'completed']);
                }
                return response()->json(['status'=>'failed'],500);
            }else{
                $uid = rand(0, 1000);
                \App\Jobs\ExportCustomers::dispatch($uid, $request->search, $request->status);
                return response()->json(['uid'=>$uid, 'status'=>'start']);
            }
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * export customers.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function export(Request $request)
    {
        $user = $request->user('api');
        if($user->can('customers')){
            if($request->exists('uid')){
                $status = Cache::get('export-'.$request->uid);
                if($status === 'completed') {
                    $filePath = Storage::disk('local')->path("customers/$request->uid");
                    return response()->download($filePath, 'customers.xlsx');
                }
            }
            return response()->json(['status'=>'failed'],500);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * register activity.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function activity(Request $request){
        $column = $request->input('column');
        $user = $request->user('api');
        if($user && $user->customer){
            $user->customer->increaseRecord($column);
            \App\Jobs\Activity::dispatch($user->customer);
        }
        return response()->json(['status'=>'ok']);
    }
    /**
     * redirect youtubelink.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function link(Request $request){
        $exist = false;
        if($request->exists('shortcode_id')){
            $shortcode = Shortcode::find($request->input('shortcode_id'));
            if($shortcode){
                $exist = true;
                $redirectLink = $shortcode->video_url;
                if($redirectLink == null)$redirectLink = $shortcode->link;
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
    /**
     * trigger workout.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function triggerWorkout(Request $request){
        $user = $request->user('api');
        if($user->customer){
            $user->customer->active_email=!$user->customer->active_email;
            $user->customer->save();
            \App\Jobs\Activity::dispatch($user->customer);
            return response()->json(['status'=>$user->customer->active_email]);
        }
    }
    /**
     * trigger notofiable.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function triggerNotifiable(Request $request){
        $user = $request->user('api');
        if($user->customer){
            $user->customer->notifiable=!$user->customer->notifiable;
            $user->customer->save();
            \App\Jobs\Activity::dispatch($user->customer);
            return response()->json(['status'=>$user->customer->notifiable]);
        }
        return response()->json(['status'=>'failed'],403);
    }
    /**
     * show referral coupon.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function showReferralCoupon(Request $request){
        $user = $request->user('api');
        if($user->customer){
            $referralCoupon = $user->customer->findReferralCoupon();
            \App\Jobs\Activity::dispatch($user->customer);
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
    /**
     * get referral.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function referral(Request $request){
        $user = $request->user('api');
        if($user->customer && $user->customer->hasActiveSubscription()){
            $referralUrl = $user->customer->findReferralUrl();
            \App\Jobs\Activity::dispatch($user->customer);
            $discount = Setting::getReferralDiscount();
            return response()->json(['referralUrl'=>$referralUrl,'discount'=>$discount]);
        }
        return response()->json(['status'=>'failed']);
    }
    /**
     * get partners.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function partners(Request $request){
        $user = $request->user('api');
        if($user->customer && $user->customer->hasActiveSubscription()){
            $partners = $user->customer->findPartners();
            \App\Jobs\Activity::dispatch($user->customer);
            return response()->json(['partners'=>$partners]);
        }
        return response()->json(['status'=>'failed']);
    }
    /**
     * show a customer credit card.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function ccard(Request $request){
        $user = $request->user('api');
        if($user->customer){
            \App\Jobs\Activity::dispatch($user->customer);
            $paymentToken = PaymentTocken::whereCustomerId($user->customer->id)->first();
            if($paymentToken)return response()->json(['number'=>$paymentToken->last4]);
        }
        return response()->json(['number'=>false]);
    }
    /**
     * show alternate shortcode.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function alternateShortcode(Request $request){
        $user = $request->user('api');
        if($user->customer){
            $shortcode = Shortcode::find($request->shortcode_id);
            if($shortcode){
                $customerShortcode = CustomerShortcode::updateOrCreate(
                    ['customer_id' => $user->customer->id, 'shortcode_id' => $request->shortcode_id],
                    ['alternate_id' => $request->alternate_id]
                );
                return response()->json(['item'=>$customerShortcode]);
            }
        }
        return response()->json(['status'=>false],401);
    }
    /**
     * get public customers and private customers.
     * 
     * This endpoint gets all public customers and private customers with active subscriptions.
     * @authenticated
     * @response {
     *  "people"=>[
     *      customers    
     *  ],
     *  "privateProfiles"=>[
     *  customers
     *  ],
     * }
     */
    public function people(Request $request){
        $user = $request->user('api');
        if($user->customer){
            [$people, $privateProfiles] = $user->customer->getPeople();
            return response()->json(['people'=>$people,'privateProfiles'=>$privateProfiles]);
        }
        return response()->json(['status'=>false],401);
    }    
    /**
     * get newsfeed or suggested posts.
     * 
     * This endpoint returns newsfeed or suggested posts(posts id desc) .
     * @authenticated
     * @bodyParam suggested integer required    //if it is 0, it returns newsfeed, if it is 1, it returns suggested posts;
     * @bodyParam post_id integer //latest post id, when appending post, it is used
     * @response {
     * 'newsfeed':
     * [
     *  {
     *      id:'1',
     *      content:"this is general post",
     *      type:"general", // workout    
     *      medias:[
     *          {
     *          url:"https://aws3.domain.com/shop.png"
     *          type:"image", //or video,
     *          width:400,
     *          height:300,
     *      }
     *      ]
     *  },
     *  {
     *      id:'2',
     *      content:"this is workout post",
     *      type:"workout", // 
     *  },
     *  {
     *      id:'3',
     *      type:"shop", // bechmark, blog, evento,
     *      title:"This is shop title",
     *      content:"This is shop content",
     *      contentType:"html", //blog, evento
     *      shopUsername:"shopusername",
     *      shopLogo:{small:"https://aws3.domain.com/shop.png"},
     *      customers:[
     *          {
     *              id:345,
     *              chat_id:"4242"
     *          }
     *      ]
     *  }
     *  {
     *      id:'2021-04-30-0',
     *      type:"birthday", // 
     *      label:"15 de abril",
     *      customers:[
     *          {
     *              id:345,
     *              chat_id:"4242"
     *          }
     *      ]
     *  }
     * ,
     *  {
     *      id:'9',
     *      type:"join", // new customer
     *      customer:
     *          {
     *              id:345,
     *              first_name:"a",
     *              chat_id:"4242"
     *          }
     *  }
     * ,
     *  {
     *      id:'2021-04-23-w',
     *      type:"workout-post", // workout content
     *      title:"23 de abril, 2021",
     *      content:"<p>This is workout content</p>",
     *      contentType:"html",
     *  }
     * ],
     * 'next':true //if true, it has next element, if false, it hasn't next element,
     * }
     */
    public function newsfeed(Request $request){
        $user = $request->user('api');
        if($user->customer){
            [$newsfeed, $next] = $user->customer->getNewsfeed($request->post_id,$request->suggested);
            return response()->json(['newsfeed'=>$newsfeed,'next'=>$next]);
        }
        return response()->json(['status'=>false],401);
    }
    /**
     * get old newsfeed.
     * 
     * This endpoint returns already reading newsfeed.
     * @authenticated
     * @bodyParam post_id integer required //latest post id, when appending post, it is used if post_id is -1, it returns first post list
     * @response {
     * }
     */
    public function oldnewsfeed(Request $request){
        $user = $request->user('api');
        if($user->customer){
            [$newsfeed,$next] = $user->customer->getOldNewsfeed($request->post_id);
            return response()->json(['oldNewsfeed'=>$newsfeed,'next'=>$next]);
        }
        return response()->json(['status'=>false],401);
    }
    /**
     * show customer profile.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function profile($id, Request $request){
        $user = $request->user('api');
        if($user->customer || $user->can('social')){
            $customer = Customer::find($id);
            $customer->getSocialDetails($user->customer->id);
            $customer['medals'] = $customer->findMedal();
            $customer['type'] = "customer";
            return response()->json($customer);
        }
        return response()->json(['status'=>false],401);
    }
    /**
     * update self's bumbells weight
     * @authenticated
     * @bodyParam weight float required
     */
    public function updateDumbellsWeight(Request $request){
        $user = $request->user('api');
        $validator = Validator::make($request->all(), array('weight'=>['required','numeric']));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()],422);
        }
        if($user->customer){
            $user->customer->dumbells_weight = $request->weight;
            $user->customer->save();
            $me = User::findDetails($user);
            return response()->json(['user' => $me]);
        }
        return response()->json(['status'=>false],401);
    }
    /**
     * get all customers except me containing expired customers
     * @authenticated
     */
    public function all(Request $request){
        $user = $request->user('api');
        if(isset($user->customer)){
            $search = $request->search;
            $where = Customer::where(function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search){
                    $q->where('active','=','1');
                    $q->where('name','like',"%$search%");
                });
            })->where('id','!=',$user->customer->id);
            $customers = $where->get();
            foreach($customers as $customer){
                $customer->display = $customer->first_name.' '.$customer->last_name;
                $customer->getAvatar();
                $customer->chat_id = $customer->user->chat_id;
                unset($customer->user);
                if($customer->mutedRelations->count()>0){
                    $customer['relation'] = $customer->mutedRelations[0]->pivot->status;
                }
            }
            return response()->json([
                'customers'=>$customers
            ]);
        }
        return response()->json([
            'errors' => ['result'=>[['error'=>'failed']]]
        ], 403);
    }
    /**
     * update push notification token
     * @authenticated
     * @bodyParam token string required
     */
    public function pushNotificationToken(Request $request){
        $user = $request->user('api');
        $validator = Validator::make($request->all(), array('token'=>['required']));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()],422);
        }
        if($user->customer){
            $user->customer->push_notification_token = $request->token;
            $user->customer->save();
            $me = User::findDetails($user);
            return response()->json(['user' => $me]);
        }
        return response()->json(['status'=>false],401);
    }
}