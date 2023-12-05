<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EventoComment;
/**
 * @group Evento Comment
 *
 * APIs for managing  evento comment
 */

class EventoCommentController extends Controller
{
    /**
     * search evento comments.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function index(Request $request){
        $comment = new EventoComment;
        $comment->assignFrontSearch($request);    
        $comments = $comment->search();
        return response()->json(['comments'=>$comments]);
    }
    /**
     * create a evento comment.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function store(Request $request){
        $user = $request->user();
        try {
            DB::beginTransaction();
            $parentId = null;
            if($request->exists("parent_id")){
                $comment = EventoComment::find($request->parent_id);
                $parentId = $request->parent_id;
                if($comment){
                    if($comment->level1 == 0){
                        $maxlevel = $comment->level0;
                        $maxlevel1 = 0;
                    }else{
                        $comment = EventoComment::find($request->parent_id);
                        $maxlevel = $comment->level0;
                        $maxlevel1 = DB::table("evento_comments")->where('parent_id',$parentId)->max('level1');
                    }
                    if($maxlevel == null)$maxlevel = 0;
                    $maxlevel1++;
                }else{
                    throw new \Exception('There is no comment.');
                }
            }else{
                $maxlevel = DB::table("evento_comments")->where('evento_id',$request->evento_id)->max('level0');
                if($maxlevel == null)$maxlevel = 0;
                $maxlevel++;
                $maxlevel1 = 0;
            }
            $comment = EventoComment::create([
                'customer_id'=>$user->customer->id,
                'content'=>$request->content,
                'evento_id'=>$request->evento_id,
                'parent_id'=>$parentId,
                'level0'=>$maxlevel,
                'level1'=>$maxlevel1,
                ]);
            $comment->save();
            DB::commit();
            $comment->customer->getAvatar();
            $commentsCount = EventoComment::whereEventoId($request->evento_id)->count();
            if($maxlevel1>0){
                $condition = ['evento_id'=>$request->evento_id,'to_level0'=>$comment->level0,'to_level1'=>$comment->level1];
                list($children, $nextChildrenCount) = EventoComment::findRepliesByCondition($condition, $user);    
                return response()->json([
                    'comment'=>$comment,
                    'comments'=>$children,
                    'nextChildrenCount'=>$nextChildrenCount,
                    'commentsCount'=>$commentsCount,
                    ]
                );        
            }else{
                $viewComments = EventoComment::findByCondition($comment, $user);
                return response()->json([
                    'comment'=>$comment,
                    'comments'=>$viewComments,
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
     * delete a evento comment.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function destroy($id,Request $request){
        $user = $request->user();
        $comment = EventoComment::find($id);
        if($comment && $comment->customer_id == $user->customer->id){
            $isReply = false;
            if($comment->level1>0){
                $toComment = EventoComment::find($request->to_id);
                $isReply = true;
                $toLevel1 = $toComment->level1;
                $toLevel0 = $toComment->level0;
            }
            $evento = $comment->evento;
            if($isReply){
                $comment->delete();
            }else{
                EventoComment::where('level0',$comment->level0)->delete();
            }
            $commentsCount = EventoComment::whereEventoId($evento->id)->count();
            if($isReply){
                $condition = ['evento_id'=>$evento->id,'to_level0'=>$toLevel0,'to_level1'=>$toLevel1];
                list($children, $nextChildrenCount) = EventoComment::findRepliesByCondition($condition, $user);
                return response()->json([
                    'children'=>$children,
                    'nextChildrenCount'=>$nextChildrenCount,
                    'commentsCount'=>$commentsCount
                    ]
                    );
            }
            $condition = ['evento_id'=>$evento->id];
            $viewComments = EventoComment::findByConditionWith($condition, $user);
            return response()->json([
                'comments'=>$viewComments,
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
     * show a evento comment.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function show($id,Request $request){
        $comment = EventoComment::find($id);
        return response()->json($comment);    
    }
    /**
     * update a evento comment.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function update($id,Request $request)
    {
        $comment = EventoComment::find($id);
        $user = $request->user();
        if($user->customer->id != $comment->customer_id)return response()->json(['status'=>'failed'],403);
        try {
            DB::beginTransaction();
            $comment->fill(['content'=>$request->content]);
            $comment->save();
            DB::commit();
            $comment->customer->getAvatar();
            return response()->json(array('status'=>'ok','comment'=>$comment));
        
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(array('status'=>'failed'));
        }
    }

}