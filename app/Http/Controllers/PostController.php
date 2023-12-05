<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;
use App\Models\Post;
use App\Models\Media;
use App\Jobs\DeleteAsyncPost;
use Illuminate\Support\Facades\Storage;
/**
 * @group Post    on social part
 *
 * APIs for managing post
 */

class PostController extends Controller
{
    /**
     * search posts for specific customer.
     * 
     * This endpoint returns 3 posts from post_id order by post id desc
     * @authenticated
     * @bodyParam customer_id integer required
     * @bodyParam post_id integer last post id
     * @response {
     *  "status":"ok",
     *  "posts":[
     *   {
     *    "id":3,
     *    "activity_id":8,
     *    "content":"content",
     *    "tagFollowers":[
     *      {
     *        customer,
     *      },
     *      {
     *        customer,
     *      },
     *      ],
     *    "medias":[],
     *    "comments":[{comment}], //it contains last comment
     *    "previousCommentsCount":5, // total (comment level)comments(not include replies) count -1
     *    "commentsCount":8, // total comments count, which contains replies level.     
     *    "likesCount":9,
     *    "like":true, // This means authenticated customer likes it
     *    "type":"general",  
     *    "customer":{customer},// it contains post creator's info
     *   },
     *   {
     *    "id":4,
     *    "activity_id":9,
     *    "content":"content",
     *    "tagFollowers":[
     *      {
     *        customer,
     *      },
     *      {
     *        customer,
     *      },
     *      ],
     *    "medias":[],
     *    "comments":[{comment}], //it contains last comment
     *    "previousCommentsCount":5, // total (comment level)comments(not include replies) count -1
     *    "commentsCount":8, // total comments count, which contains replies level.     
     *    "likesCount":9,
     *    "like":false, 
     *    "type":"workout",
     *    "workout_spanish_date":"jan 5 2022",   
     *    "workout_spanish_short_date":"jan 5 2022",   
     *    "customer":{customer},// it contains post creator's info
     *   },
     *   {
     *    "id":5,
     *    "activity_id":10,
     *    "content":"content",
     *    "tagFollowers":[
     *      {
     *        customer,
     *      },
     *      {
     *        customer,
     *      },
     *      ],
     *    "medias":[],
     *    "comments":[{comment}], //it contains last comment
     *    "previousCommentsCount":5, // total (comment level)comments(not include replies) count -1
     *    "commentsCount":8, // total comments count, which contains replies level.     
     *    "likesCount":9,
     *    "like":false, 
     *    "type":"workout",
     *    "workout_spanish_date":"jan 5 2022",   
     *    "workout_spanish_short_date":"jan 5 2022",   
     *    "customer":{customer},// it contains post creator's info
     *   },
     * ],
     * "customerProfile":true,// if true, it means the customer is public or when the customer is private, authenticated customer already followed the customer. if false, the customer is private, authenticated customer have no following relationship with the customer or blocked or muted
     * }
     */
    public function index(Request $request){
        $user = $request->user();
        $post = new Post;
        $indexData = null;            
        $profile = false;
        if($user->customer){
            $profile = $user->customer->findCustomerProfile($request);
            if($profile){
                $post->assignFrontSearch($request);
                $indexData = $post->search($user);
            }
        }else if($user->can('social')){
            $post->assignFrontSearch($request);
            $indexData = $post->search();
        }
        return response()->json(array('status'=>'ok','posts'=>$indexData,'customerProfile'=>$profile));
    }
    /**
     * create a post.
     * 
     * This endpoint.
     * @authenticated
     * @bodyParam location string
     * @bodyParam content string  contains multi mentioned user such as @[Marlon Cañas](132)
     * @bodyParam tag_followers integer[]
     * @bodyParam medias file[]  video, image max size 200M
     * @response {
     *  "status:"ok",
     *  "post":{
     *  "id":3,
     *  "activity_id":5,
     *  "content":"content",
     *  "tag_followers":[4,5,8],
     * }
     * }
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(), Post::validateRules());
        $user = $request->user();
        try {
            \DB::beginTransaction();
            $activity = new Activity;
            $activity->save();
            $post = new Post;
            $post->fill(['activity_id'=>$activity->id,'customer_id'=>$user->customer->id,'content'=>$request->content,'location'=>$request->location,'tag_followers'=>json_decode($request->tag_followers)]);
            // if($request->exists('workout_date')){
            //     $post->workout_date = $request->workout_date;
            //     $post->type = 'workout';
            // }
            $post->save();
            if(isset($request->medias)){
                $post->uploadMedias($request->medias);
            }else{
                Post::withoutEvents(function () use ($post) {
                    $post->status = 1;
                    $post->save();
                });
            }
            \DB::commit();
            return response()->json(array('status'=>'ok','post'=>$post));
        
        } catch (Throwable $e) {
            \DB::rollback();
            return response()->json(array('status'=>'failed'));
        }
        
    }
    /**
     * delete a post.
     * 
     * This endpoint.
     * @urlParam post integer required the id of post
     * @authenticated
     * @response scenario=success {
     *      "status":"1",
     *      "msg":"success"
     * }
     * @response scenario=failed {
     *      "status":"0",
     *      "msg":"fail"
     * }
     */
    public function destroy($id,Request $request){
        $post = Post::find($id);
        $user = $request->user();
        if($user->type=="customer" && $user->customer->id == $post->customer_id || $user->can('social')){
            if($post){
                $post->status = 0;
                $post->save();
                DeleteAsyncPost::dispatch($id);
            }
            if ($post){
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
            return response()->json(array('status'=>'forbidden'), 403);
        }
    }
    /**
     * show a post.
     * 
     * This endpoint.
     * @authenticated
     * @urlParam post integer required
     * @bodyParam comment integer when comment = 1, it contains all comments, but not include replies
     * @response scenario="comment not 1"{
     *    "id":5,
     *    "activity_id":10,
     *    "content":"content",
     *    "tagFollowers":[
     *      {
     *        customer,
     *      },
     *      {
     *        customer,
     *      },
     *      ],
     *    "medias":[],
     *    "comments":[{comment}], //it contains last comment
     *    "previousCommentsCount":5, // total (comment level)comments(not include replies) count -1
     *    "commentsCount":8, // total comments count, which contains replies level.     
     *    "likesCount":9,
     *    "like":false, 
     *    "type":"general",
     *    "customer":{customer},// it contains post creator's info
     * }
     * @response scenario="comment 1"{
     *    "id":5,
     *    "activity_id":10,
     *    "content":"content",
     *    "tagFollowers":[
     *      {
     *        customer,
     *      },
     *      {
     *        customer,
     *      },
     *      ],
     *    "medias":[],
     *    "comments":[{comment}], //it contains all comments
     *    "previousCommentsCount":0, 
     *    "commentsCount":8, // total comments count, which contains replies level.     
     *    "likesCount":9,
     *    "like":false, 
     *    "type":"general",
     *    "customer":{customer},// it contains post creator's info
     * }
     */
    public function show($id,Request $request){
        $user = $request->user();
        $post = Post::find($id);
        $condition = null;
        if($request->comment == 1){
            $condition = ['from_id'=>-1,'count'=>-1];
        }
        $post->extend($condition, $user);
        return response()->json($post);    
    }
    /**
     * update a post.
     * 
     * This endpoint update post data. but image or videos has not been update immediately, because fitemos saves medias asynchronously
     * @authenticated
     * @urlParam post integer required
     * @bodyParam location string
     * @bodyParam content string  contains multi mentioned user such as @[Marlon Cañas](132)
     * @bodyParam tag_followers integer[]
     * @bodyParam media_ids integer[] original medias
     * @bodyParam medias file[]  new meidas
     * @response {
     *  "status:"ok",
     *  "post":{
     *  "id":3,
     *  "activity_id":5,
     *  "content":"content",
     *  "tag_followers":[4,5,8],
     * }
     * }
     */
    public function update($id,Request $request)
    {
        $post = Post::find($id);
        $validator = Validator::make($request->all(), Post::validateRules());
        $user = $request->user();
        if($user->type=="customer" && $user->customer->id == $post->customer_id || $user->can('social')){
            try {
                \DB::beginTransaction();
                $oldMedias = $post->medias;
                $post->fill(['content'=>$request->content,'location'=>$request->location,'tag_followers'=>json_decode($request->tag_followers)]);
                $post->save();
                if(isset($request->media_ids)){
                    $mediaIds = $request->media_ids;
                    foreach($mediaIds as $mediaId){
                        $oldMedias = $oldMedias->reject(function($media,$key) use ( $mediaId ){
                            return $media->id == $mediaId;
                        });
                    }
                }
                if($oldMedias&&$oldMedias->count()){
                    $oldMedias->each(function($media){
                        Storage::disk('s3')->delete($media->src);
                        $media->delete();
                    });
                }            
                if(isset($request->medias)){
                    $post->uploadMedias($request->medias);
                }
                \DB::commit();
                return response()->json(array('status'=>'ok','post'=>$post));
            
            } catch (Throwable $e) {
                \DB::rollback();
                return response()->json(array('status'=>'failed'), 501);
            }
        }else{
            return response()->json(array('status'=>'forbidden'), 403);
        }
    }
    /**
     * get posts by ids.
     * 
     * This endpoint gets current posts with ids
     * @authenticated
     * @bodyParam ids integer[]
     * @response {
     *   "items":[
     *      {
     *          post
     *      },
     *      {
     *          post
     *      },
     *      {
     *          post
     *      },
     * ]
     * }
     */
    public function subNewsfeed(Request $request){
        $user = $request->user();
        if($user->customer){
            $posts = Post::with('customer')->whereIn('id',$request->ids)->get();
            $result = [];
            foreach($posts as $post){
                $post->extend(null, $user);
                array_push($result, $post);
            }
            return response()->json(['items'=>$result]);
        }
        return response()->json(['status'=>'failed'], 401);
    }
    /**
     * get random medias for a customer.
     * 
     * This endpoint get random medias of a customer who is public,  whom authenticated customer followed (not blocked, muted)
     * @authenticated
     * @urlParam customerId integer required
     * @response {
     *      "self":[    // customer's 6 medias random order
     *          {media},
     *          {media}
     *      ],
     *      "other":[ // other customers' 12 medias  random order
     *          {media},
     *          {media}
     *      ]
     * }
     */
    public function randomMedias($customerId,Request $request){
        $user = $request->user();
        if($user->customer){
            // DB::enableQueryLog();
            $self = null;            
            $other = null;            
            $profile = false;
            if($user->customer){
                $profile = $user->customer->findCustomerProfile($request);
                if($profile){
                    $self = Media::whereHas('post',function($query) use ($customerId){
                        $query->whereStatus(1)
                              ->whereCustomerId($customerId);
                    })->inRandomOrder()->limit(6)->get();
                    // dd(DB::getQueryLog());            
                    $other = Media::whereHas('post',function($query) use ($customerId){
                        $query->whereStatus(1)
                              ->where('customer_id','!=',$customerId);
                    })->inRandomOrder()->limit(12)->get();
                }
            }    
            return response()->json(['self'=>$self,'other'=>$other]);
        }
        return response()->json(['status'=>'failed'], 401);
    }
    /**
     * get medias for a customer.
     * 
     * This endpoint.
     * @authenticated
     * This endpoint get latest 20 medias of a customer who is public,  whom authenticated customer followed (not blocked, muted)
     * @authenticated
     * @bodyParam customer_id integer required
     * @bodyParam media_id integer // from media_id
     * @response {
     *      "medias":[    // customer's 20 medias from media_id order by id desc
     *          {media},
     *          {media}
     *      ],
     * }
     */

    public function medias(Request $request){
        $user = $request->user();
        if($user->customer){
            $profile = $user->customer->findCustomerProfile($request);
            $medias = null;
            if($profile){
                $customerId = $request->customer_id;
                // DB::enableQueryLog();
                $where = Media::whereHas('post',function($query) use ($customerId){
                    $query->whereStatus(1)
                          ->whereCustomerId($customerId);
                });
                if($request->media_id>0){
                    $where = $where->where('id','<',$request->media_id);
                }
                $medias = $where->orderBy('id','desc')->limit(20)->get();
            }

            // dd(DB::getQueryLog());            
            return response()->json(['medias'=>$medias]);
        }
        return response()->json(['status'=>'failed'], 401);
    }
    /**
     * read a post.
     * 
     * This endpoint save reading time on post 
     * @authenticated
     * @urlParam id integer required
     * @response {
     * }
     */
    public function read($id,Request $request){
        $user = $request->user();
        $post = Post::find($id);
        if($post){
            $reading = DB::table('reading_posts')->where('post_id',$id)->where('customer_id',$user->customer->id)->first();
            if(!$reading){
                DB::table('reading_posts')->insert([
                    'post_id' => $id,
                    'customer_id' => $user->customer->id,
                    "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                    "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
                ]);        
            }
        }
        return response()->json(['status'=>'ok']);
    }
    /**
     * get Posts with ids and comment condition.
     * 
     * This endpoint.
     * @authenticated
     * @bodyParam ids object[] required
     * @bodyParam ids[].id integer required post_id
     * @bodyParam ids[].from_id integer required from comment_id
     * @bodyParam ids[].to_id integer required to comment_id
     * @response {
     *  "posts":[
     *   {
     *    "id":3,
     *    "activity_id":8,
     *    "content":"content",
     *    "tagFollowers":[
     *      {
     *        customer,
     *      },
     *      {
     *        customer,
     *      },
     *      ],
     *    "medias":[],
     *    "comments":[{comment}, {comment}], comments of from_id and to_id
     *    "previousCommentsCount":5, 
     *    "nextCommentsCount":1,  //other customer recent comments count
     *    "commentsCount":8, // total comments count, which contains replies level.     
     *    "likesCount":9,
     *    "like":true, // This means authenticated customer likes it
     *    "type":"general",  
     *    "customer":{customer},// it contains post creator's info
     *   },
     *   {
     *    "id":4,
     *    "activity_id":9,
     *    "content":"content",
     *    "tagFollowers":[
     *      {
     *        customer,
     *      },
     *      {
     *        customer,
     *      },
     *      ],
     *    "medias":[],
     *    "comments":[{comment}, {comment}], comments of from_id and to_id
     *    "previousCommentsCount":5, 
     *    "nextCommentsCount":1,  //other customer recent comments count
     *    "commentsCount":8, // total comments count, which contains replies level.     
     *    "likesCount":9,
     *    "like":false, 
     *    "type":"workout",
     *    "workout_spanish_date":"jan 5 2022",   
     *    "workout_spanish_short_date":"jan 5 2022",   
     *    "customer":{customer},// it contains post creator's info
     *   },
     *   {
     *    "id":5,
     *    "activity_id":10,
     *    "content":"content",
     *    "tagFollowers":[
     *      {
     *        customer,
     *      },
     *      {
     *        customer,
     *      },
     *      ],
     *    "medias":[],
     *    "comments":[{comment}, {comment}], comments of from_id and to_id
     *    "previousCommentsCount":5, 
     *    "nextCommentsCount":1,  //other customer recent comments count
     *    "commentsCount":8, // total comments count, which contains replies level.     
     *    "likesCount":9,
     *    "like":false, 
     *    "type":"workout",
     *    "workout_spanish_date":"jan 5 2022",   
     *    "workout_spanish_short_date":"jan 5 2022",   
     *    "customer":{customer},// it contains post creator's info
     *   },
     * ]
     * }
     */
    public function sync(Request $request){
        $ids = [];
        $conditions = [];
        foreach($request->ids as $item){
            $ids[] = $item['id'];
            $conditions[$item['id']] = $item;
        }
        $posts = Post::whereIn('id',$ids)->orderBy('id','desc')->get();
        foreach($posts as $post){
            $post->extend($conditions[$post->id], $request->user());
        }
        return response()->json(['posts'=>$posts]);
    }
    /**
     * get a today workout post.
     * 
     * This endpoint get current workout post, after 19, it returns tomorrow workout post
     * @authenticated
     * @response {
     * }
     */
    public function workout(Request $request){
        $user = $request->user();
        if($user&&$user->customer){
            $post = $user->customer->generateWorkoutPost();
            return response()->json($post);
        }
        return response()->json(null);
    }
}

