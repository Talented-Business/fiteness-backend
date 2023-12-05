<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Customer;
use App\Company;
use App\Config;
use App\Models\Post;

/**
 * @group Search   
 *
 * APIs for searching people, eventos, posts
 */

class SearchController extends Controller
{
    /**
     * search customers, shops, posts.
     * 
     * This endpoint searchs customers and shops and posts
     * @authenticated
     * @bodyParam search string required
     * @response {
     *  "people":[{customer}],
     *  "shop":[{company}],
     *  "posts":[{post}],
     * }
     */
    public function all(Request $request){
        $user = $request->user('api');
        if($user->customer){
            $search = $request->search;
            $customers = Customer::where(function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search){
                    $q->where('active','=','1');
                    $q->where('name','like',"%$search%");
                });
                $query->whereHas('subscriptions', function($q){
                    $q->where('status','=',"Active");
                });
            })->where('id','!=',$user->customer->id)->limit(4)->get();
            foreach($customers as $customer){
                $customer->getAvatar();
                $customer->getSocialDetails($user->customer->id);
            }
            // DB::enableQueryLog();
            $shops = Company::where('name','like',"%$search%")
                ->whereStatus('active')
                ->where(function($q) use ($user){
                    $q->whereHas('countries',function($query) use ($user){
                        $query->where('country','=',strtoupper($user->customer->country_code));
                    })
                    ->where('is_all_countries','=','no')
                    ->orWhere('is_all_countries','=','yes');
                    // ->whereHas('products',function($query) use ($user){
                    //     $query->where('status', '=', "Active")
                    //         ->where('expiration_date', '>=', $user->customer->currentDate());
                    // })
                })
                ->limit(4)->get();
            $size = 'x-small';
            foreach($shops as $index=>$item){
                $logo= $shops[$index]['logo'];
                $shops[$index]['logo'] = $item->getImageSize($logo,$size);
            }        
            $posts = \App\Models\Post::with('medias')->where('searchable_content','like',"%$search%")->limit(4)->get();
            foreach($posts as $post){
                $post->extend(null, $user);
            }    
            // dd(DB::getQueryLog());
            return response()->json([
                'searchResult' => ['people'=>$customers,'shops'=>$shops,'posts'=>$posts]
            ]);
        }
        return response()->json([
            'errors' => ['result'=>[['error'=>'failed']]]
        ], 403);
    }
    /**
     * search customers.
     * 
     * This endpoint.
     * @authenticated
     * @bodyParam search string required
     * @response {
     *  "customers":[{customer}],
     * }
     */
    public function customers(Request $request){
        $user = $request->user('api');
        if($user->customer){
            $search = $request->search;
            $where = Customer::where(function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search){
                    $q->where('active','=','1');
                    $q->where('name','like',"%$search%");
                });
                $query->whereHas('subscriptions', function($q){
                    $q->where('status','=',"Active");
                });
            })->where('id','!=',$user->customer->id);
            if($request->id>0){
                $where = $where->where('id','<',$request->id);
            }
            $customers = $where->orderBy('id','desc')->limit(20)->get();
            foreach($customers as $customer){
                $customer->getAvatar();
                $customer->getSocialDetails($user->customer->id);
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
     * search companies.
     * 
     * This endpoint.
     * @authenticated
     * @bodyParam search string required
     * @response {
     *  "companies":[{company}],
     * }
     */
    public function companies(Request $request){
        $user = $request->user('api');
        if($user->customer){
            $search = $request->search;
            $where = Company::where('name','like',"%$search%")
            ->whereStatus('active')
            ->where(function($q) use ($user){
                $q->whereHas('countries',function($query) use ($user){
                    $query->where('country','=',strtoupper($user->customer->country_code));
                })
                ->where('is_all_countries','=','no')
                ->orWhere('is_all_countries','=','yes');
                // ->whereHas('products',function($query) use ($user){
                //     $query->where('status', '=', "Active")
                //         ->where('expiration_date', '>=', $user->customer->currentDate());
                // })
            });
            if($request->id>0){
                $where = $where->where('id','<',$request->id);
            }
            $companies = $where->orderBy('id','desc')->limit(20)->get();
            $size = 'x-small';
            foreach($companies as $index=>$item){
                $logo= $companies[$index]['logo'];
                $companies[$index]['logo'] = $item->getImageSize($logo,$size);
            }        
            return response()->json([
                'companies'=>$companies
            ]);
        }
        return response()->json([
            'errors' => ['result'=>[['error'=>'failed']]]
        ], 403);
    }
    /**
     * search posts.
     * 
     * This endpoint.
     * @authenticated
     * @bodyParam search string required
     * @response {
     *  "posts":[{post}],
     * }
     */
    public function posts(Request $request){
        $user = $request->user('api');
        if($user->customer){
            $search = $request->search;
            $where = \App\Models\Post::with('medias')->where('searchable_content','like',"%$search%");
            if($request->id>0){
                $where = $where->where('id','<',$request->id);
            }
            $posts = $where->orderBy('id','desc')->limit(20)->get();
            foreach($posts as $post){
                $post->extend(null, $user);
            }
            return response()->json([
                'posts'=>$posts
            ]);
        }
        return response()->json([
            'errors' => ['result'=>[['error'=>'failed']]]
        ], 403);
    }
    /**
     * find customer or shop by username.
     * 
     * This endpoint
     * It find only shop unauthenticated, and it searchs customer or shop when authenticated.
     * @bodyParam u string required
     * @response scenario=customer{
     *      "id":5,
     *      "type":"customer",
     *      "username":"unique_user_name",    
     *      "first_name":"First",
     *      "last_name":"Last",
     *      "postCount":10,
     *      "following":{follow},
     *      "followers":[{follow}],
     *      "followings":[{follow}],
     *      "relation":false,//it shows blocked or muted
     *      "medals":{
     *           'fromWorkout':100,// current level workout number
     *           'fromWorkoutImage':$src,//image
     *           'toWorkout':200,// next level workout number
     *           'toWorkoutImage':$toWorkoutImage,//image
     *           'workoutCount':75,// this total completed workout count
     *           'levelMedalImage':$src // level medal image
     *           'fromMonthWorkout':15,// current level month workout number
     *           'fromMonthWorkoutImage':$src,// current level month workout image
     *           'toMonthWorkout':25,// next level month workout number
     *           'toMonthWorkoutImage':$src,// next level month workout image
     *           'monthWorkoutCount':45, // this month total completed workout count 
     *           'monthWorkoutTotal':46, // this month total workout count
     *           'monthShortName':"Feb",// spanish month short name
     *           'monthPercent':$monthPercent
     *      },
     * }
     * @response scenario=company{
     *      "id":5,
     *      "type":"company",
     *      "username":"unique_user_name",    
     *      "name":"name",
     *      "description":"description",
     *      "phone":"phone",
     *      "mail":"email@gmail.com",
     *      "is_all_countries":"yes",// or "no"
     *      "mobile_phone":"1231231",
     *      "website_url":"https://www.fitemos.com",
     *      "address":"address",
     *      "facebook":"facebook",
     *      "instagram":"instagram",
     *      "twitter":"twitter",
     *      "horario":"horario",
     *      "logo":"src"
     * }
     * @response  status=403 scenario="Not found"{
     * }
     */
    public function username(Request $request){
        $user = $request->user('api');
        $username = $request->u;
        if($user && $user->customer){
            $customer = Customer::whereUsername($username)->first();
            if($customer){
                $customer->type = 'customer';
                if($customer->hasActiveSubscription()){
                    $customer->getSocialDetails($user->customer->id);
                    $customer['medals'] = $customer->findMedal();    
                    return response()->json($customer);    
                }
                $customer->getSocialDetails($user->customer->id);
                $customer['medals'] = $customer->findMedal();    
                return response()->json($customer);
            }
            $company = Company::whereUsername($username)->first();
            if($company){
                $company->type = 'company';
                if($company->logo)$company->logo = secure_url("storage/".$company->logo);                
                return response()->json($company);
            }
        }else{
            $company = Company::where('username','=',$username)->whereStatus('Active')->first();
            if($company){
                $company->type = 'company';
                if($company->logo)$company->logo = secure_url("storage/".$company->logo);                
                return response()->json($company);
            }
        }
        return response()->json(null, 403);
    }
    /**
     * get notifications.
     * 
     * This endpoint shows latest 30 notifications.
     * @authenticated
     * @response {
     *  notifications:[{notification}]
     * }
     */
    public function notifications(Request $request){
        $user = $request->user('api');
        $notifications = \App\Models\Notification::whereCustomerId($user->customer->id)->orderBy('id','desc')->limit(30)->get();
        foreach($notifications as $notification){
            switch($notification->action_type){
                case "customer":
                    $notification->action = Customer::find($notification->action_id);
                    $notification->action->getAvatar();
                    break;
            }
        }
        return response()->json(['notifications'=>$notifications]);
    }
    /**
     * sync.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function sync($id){
        $customer = Customer::find($id);
        if($customer) {
          $customer = strtotime($customer->updated_at);
        }
        else $customer = null;
        $config = new Config;
        $publicProfile = $config->findByName('public_profile');        
        $newsfeed = null;
        $notification = null;
        return response()->json([
            'customer'=>$customer,
            'publicProfile'=>$publicProfile,
            'notification'=>$notification,
            'newsfeed'=>$newsfeed,
        ]);
    }
    /**
     * add the list of all members with this order:  (active or inactive Will be included)
     *   Users with more interactions ( total workouts / latest 30 days   🡪 but now sure if Will exist Good performance calculating this each time the sectin Will be loaded)  
     *   Users with pictures
     *   Users without pictures
     *   Inactive users 
     * 
     * @authenticated
     * 
     * @getParam page integer required
     * 
     * @response {
     *  "customers":[{customer}],
     * }
     */
    public function members(Request $request){
        $user = $request->user('api');
        if($request->exists('page')){
            $page = $request->input('page');
        }else{
            $page = 1;
        }
        if($user->customer){
            $where = Customer::where(function($query) {
                $query->whereHas('user', function($q){
                    $q->where('active','=','1');
                });
            })->where('id','!=',$user->customer->id);
            if($request->id>0){
                $where = $where->where('id','<',$request->id);
            }
            $config = new Config;
            $orderColumn = $config->findByName('customer_display_order_column');   
            if($orderColumn === null) $orderColumn = 'first_order_id';
            $customers = $where->orderBy($orderColumn,'desc')->skip(($page - 1) * 20)->take(20)->get();
            foreach($customers as $customer){
                $customer->getAvatar();
                $customer->getSocialDetails($user->customer->id);
            }
            return response()->json([
                'customers'=>$customers
            ]);
        }
        return response()->json([
            'errors' => ['result'=>[['error'=>'failed']]]
        ], 403);
    }
}