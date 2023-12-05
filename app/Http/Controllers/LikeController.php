<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Like;
/**
 * @group Like   
 *
 * APIs for managing  likes for post and comment on social part
 */

class LikeController extends Controller
{
    /**
     * create a like.
     * 
     * This endpoint.
     * @authenticated
     * @bodyParam activity_id integer required
     * @response {
     * }
     */
    public function store(Request $request){
        $user = $request->user();
        if(!$request->exists('activity_id')){
            return response()->json(array('status'=>'failed'),422);
        }
        $post = Post::whereActivityId($request->activity_id)->first();
        if($post && $post->customer_id != $user->customer->id){
            if($post->customer_id>0)\App\Models\Notification::likePost($post->customer_id, $user->customer, $post);
        }
        $comment = Comment::whereActivityId($request->activity_id)->first();
        // if($comment && $comment->customer_id == $user->customer->id){
        //     return response()->json(array('status'=>'you made activity'),423);
        // }
        $like = Like::whereActivityId($request->activity_id)->whereCustomerId($user->customer->id)->first();
        if($like){
            return response()->json(array('status'=>'already exists'),421);
        }
        $like = new Like;
        $like->activity_id = $request->activity_id;
        $like->customer_id = $user->customer->id;
        $like->save();
        $like->activity->updateLikes();
        return response()->json([
            'like'=>$like]
            );        
        
    }
    /**
     * delete a like.
     * 
     * This endpoint.
     * @authenticated
     * @urlParam id integer required
     * @response {
     * }
     */
    public function destroy($id,Request $request){
        $user = $request->user();
        $activity = Activity::find($id);
        $like = Like::whereActivityId($id)->whereCustomerId($user->customer->id)->first();
        if($like){
            $like->delete();
            $activity->updateLikes();
            return response()->json([
                'activity'=>$activity
                ]);
        }
        $data=[
            'status'=>'0',
            'msg'=>'fail'
        ];
        return response()->json($data,403);
    }
    /**
     * get liker(customer) list for an activity 10 per page.
     * 
     * This endpoint.
     * @authenticated
     * @queryParam activity_id integer required
     * @queryParam pageNumber integer
     * @response {
     * [
     *  {
     *  "id"=>1,
     *  "activity_id"=>1,
     *  "customer_id"=>1,
     *  "customer"=>{
     *      "first_name"=>'first',
     *      "last_name"=>'last',
     *      "avatarUrls"=>[]
     *  }
     *  }]
     * }
     */
    public function index(Request $request){
        // $likes = Like::with('customer')->whereActivityId($request->activity_id)->get();
        $like = new Like;
        $like->assignSearch($request);
        $data = $like->search();
        return response()->json($data);
    }

}