<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;
use App\Models\Comment;
use App\Models\Post;
/**
 * @group Comment on social part
 *
 * APIs for managing  comment
 */

class CommentController extends Controller
{
    /**
     * search comments.
     * 
     * This endpoint get comments on several scenarios.
     * Only comments with level1 = 0 can have replies.
     * <p>Replies  level1 > 0.</p>
     * <p>type = appendNext, it searchs only 4 comments with level1=0 from id</p>
     * <p>type = appendNextReplies, it searchs only 4 comments(replies) with level1>0 from id</p>
     * <p>type = append, it searchs only 4 previous comments with level1=0 from id</p>
     * @authenticated
     * @bodyParam id integer required comment from id or to id
     * @bodyParam type string required appendNext or appendNextReplies or append
     * @response{
     *  "comments":[
     *      {
     *          "id":1,
     *          "activity_id":13,
     *          "post_id":3,
     *          "customer_id":9, 
     *          "parent_activity_id":4, parent item can be post or comment, so it means post or comment activity id
     *          "content":"content", it contains multi mentioned user such as @[Marlon Cañas](132) same as post content
     *          "level0":1,
     *          "level1":0,
     *          "likesCount":2,
     *          "like":false,
     *          "children":[], it exists when level1=0
     *          "nextChildrenCount":8,it exists when level1=0
     *      },
     *  ]
     * }
     */
    public function index(Request $request){
        $comment = new Comment;
        $comment->assignFrontSearch($request);    
        $comments = $comment->search();
        return response()->json(['comments'=>$comments]);
    }
    /**
     * create a comment.
     * 
     * This endpoint.
     * @bodyParam post_id integer required post ID
     * @bodyParam content string required it contains multi mentioned user such as @[Marlon Cañas](132) same as post content
     * @bodyParam parent_activity_id optional if exists, it is reply if no it is comment with level1=0
     * @bodyParam condition object optional when comment leve1 = 0 
     * @bodyParam condition.from_id integer it shows viewable comment first id
     * @authenticated
     * @response  scenario="creating comment"{
     *      "comment":{comment},// just created new comment
     *      "previousCommentsCount":5,
     *      "comments":[{comment}], comments from id to current new comment
     *      "nextCommentsCount":0,
     *      "commentsCount":14, total comment count, which contains replies
     * }
     * @response  scenario="creating comment reply"{
     *      "comment":{comment},// just created new comment
     *      "comments":[{comment}], all replies  to current new reply
     *      "nextChildrenCount":0,
     *      "commentsCount":14, total comment count, which contains replies
     * }
     * @response  status=403 scenario=failed{
     *      "status":"failed"
     * }
     */
    public function store(Request $request){
        $user = $request->user();
        try {
            DB::beginTransaction();
            $activity = new Activity;
            $activity->save();
            if($request->exists("parent_activity_id")){
                $comment = Comment::whereActivityId($request->parent_activity_id)->first();
                if($comment){
                    if($comment->level1 == 0){
                        $parentActivityId = $comment->activity_id;
                        $maxlevel = $comment->level0;
                    }else{
                        $comment = Comment::whereActivityId($comment->parent_activity_id)->first();
                        $parentActivityId = $comment->activity_id;
                        $maxlevel = $comment->level0;
                    }
                    $maxlevel1 = DB::table("comments")->where('parent_activity_id',$parentActivityId)->max('level1');
                    if($maxlevel == null)$maxlevel = 0;
                    $maxlevel1++;
                }else{
                    throw new \Exception('There is no comment.');
                }
            }else{
                $maxlevel = DB::table("comments")->where('post_id',$request->post_id)->max('level0');
                if($maxlevel == null)$maxlevel = 0;
                $post = Post::find($request->post_id);
                $parentActivityId = $post->activity_id;
                $maxlevel++;
                $maxlevel1 = 0;
            }
            $comment = Comment::create([
                'activity_id'=>$activity->id,
                'customer_id'=>$user->customer->id,
                'content'=>$request->content,
                'post_id'=>$request->post_id,
                'parent_activity_id'=>$parentActivityId,
                'level0'=>$maxlevel,
                'level1'=>$maxlevel1,
                ]);
            $comment->save();
            Post::withoutEvents(function () use ($comment) {
                $comment->post->touch();
            });
            DB::commit();
            \App\Jobs\CommentOnPost::dispatch($comment, $user->customer);
            $comment->customer->getAvatar();
            $comment->getLike($user);
            $nextCommentsCount = 0;
            $previousCommentsCount = 0;
            $commentsCount = Comment::wherePostId($request->post_id)->count();
            if($maxlevel1>0){
                $condition = ['post_id'=>$request->post_id,'to_level0'=>$comment->level0,'to_level1'=>$comment->level1];
                list($children, $nextChildrenCount) = Comment::findRepliesByCondition($condition, $user);    
                return response()->json([
                    'comment'=>$comment,
                    'comments'=>$children,
                    'nextChildrenCount'=>$nextChildrenCount,
                    'commentsCount'=>$commentsCount,
                    ]
                );        
            }else{
                $condition = ['from_id'=>$request->condition['from_id'],'to_id'=>$comment->id];
                list($previousComments, $viewComments, $nextComments) = Comment::findByCondition($condition, $user);
                $previousCommentsCount = $previousComments->count();
                $nextCommentsCount = $nextComments->count();
                return response()->json([
                    'comment'=>$comment,
                    'previousCommentsCount'=>$previousCommentsCount,
                    'comments'=>$viewComments,
                    'nextCommentsCount'=>$nextCommentsCount,
                    'commentsCount'=>$commentsCount,
                    ]
                );        
            }
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(array('status'=>'failed'));
        }
        
    }
    /**
     * delete a comment.
     * 
     * This endpoint deletes the comment and child replies and return comment struction.
     * @authenticated
     * @urlParam comment integer required comment ID
     * @bodyParam from_id integer it shows viewable comment first id when level1=0 required
     * @bodyParam to_id integer it shows viewable comment last id when level1=0 required
     * @authenticated
     * @response  scenario="creating comment"{
     *      "previousCommentsCount":5,
     *      "comments":[{comment}], comments from id to current new comment
     *      "nextCommentsCount":0,
     *      "commentsCount":14, total comment count, which contains replies
     * }
     * @response  scenario="creating comment reply"{
     *      "comments":[{comment}], all replies  to current new reply
     *      "nextChildrenCount":0,
     *      "commentsCount":14, total comment count, which contains replies
     * }
     * @response status=403 scenario=failed {
     *      "status":"0"
     * }
     */
    public function destroy($id,Request $request){
        $user = $request->user();
        $comment = Comment::find($id);
        if($comment && $comment->customer_id == $user->customer->id){
            if($request->from_id>0)$fromComment = Comment::find($request->from_id);
            $toComment = Comment::find($request->to_id);
            $isReply = false;
            if($comment->level1>0){
                $isReply = true;
                $toLevel1 = $toComment->level1;
                $toLevel0 = $toComment->level0;
            }else{
                $fromLevel0 = $fromComment->level0;
                $toLevel0 = $toComment->level0;
            }
            $post = $comment->post;
            if($isReply){
                $comment->activity->delete();
                $comment->delete();
            }else{
                $comments = Comment::whereLevel0($comment->level0)->get();
                foreach($comments as $item){
                    $item->activity->delete();
                }
                Comment::whereLevel0($comment->level0)->delete();
            }
            Post::withoutEvents(function () use ($post) {
                $post->touch();
            });
            $commentsCount = Comment::wherePostId($post->id)->count();
            if($isReply){
                $condition = ['post_id'=>$post->id,'to_level0'=>$toLevel0,'to_level1'=>$toLevel1];
                list($children, $nextChildrenCount) = Comment::findRepliesByCondition($condition, $user);
                return response()->json([
                    'children'=>$children,
                    'nextChildrenCount'=>$nextChildrenCount,
                    'commentsCount'=>$commentsCount
                    ]
                    );
            }
            $condition = ['post_id'=>$post->id,'from_level0'=>$fromLevel0,'to_level0'=>$toLevel0];
            list($previousComments, $viewComments, $nextComments) = Comment::findByConditionWith($condition, $user);
            $previousCommentsCount = $previousComments->count();
            $nextCommentsCount = $nextComments->count();
            return response()->json([
                'previousCommentsCount'=>$previousCommentsCount,
                'comments'=>$viewComments,
                'nextCommentsCount'=>$nextCommentsCount,
                'commentsCount'=>$commentsCount]
                );
            }
        $data=[
            'status'=>'0',
            'msg'=>'fail'
        ];
        return response()->json($data,403);
    }
    /**
     * show a comment.
     * 
     * This endpoint.
     * @authenticated
     * @urlParam comment integer required comment ID
     * @response {
     * }
     */
    public function show($id,Request $request){
        $comment = Comment::find($id);
        return response()->json($comment);    
    }
    /**
     * update a comment.
     * 
     * This endpoint.
     * @authenticated
     * @urlParam comment integer required comment ID
     * @bodyParam content string required it contains multi mentioned user such as @[Marlon Cañas](132) same as post content
     * @response {
     *  "status":"ok",
     *  "comment":{comment}
     * }
     */
    public function update($id,Request $request)
    {
        $comment = Comment::find($id);
        $user = $request->user();
        if($user->customer->id != $comment->customer_id)return response()->json(['status'=>'failed'],403);
        try {
            DB::beginTransaction();
            $comment->fill(['content'=>$request->content]);
            $comment->save();
            DB::commit();
            Post::withoutEvents(function () use ($comment) {
                $comment->post->touch();
            });
            $comment->customer->getAvatar();
            return response()->json(array('status'=>'ok','comment'=>$comment));
        
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(array('status'=>'failed'));
        }
    }

}