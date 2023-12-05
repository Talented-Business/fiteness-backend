<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $type;
    protected $user;
    protected $fillable = ['customer_id','post_id','activity_id','parent_activity_id','level0','level1','content'];
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
    public function activity()
    {
        return $this->belongsTo('App\Models\Activity', 'activity_id');
    }
    public function post()
    {
        return $this->belongsTo('App\Models\Post', 'post_id');
    }
    public function assignFrontSearch($request){
        $this->id = $request->id;
        $this->type = $request->type;
        $this->user = $request->user();
    }
    /**
     * param id; comment from id or to id
     * param type; appendNext or appendNextReplies 
     */
    public function search(){
        $comment = self::find($this->id);
        $where = self::with(['customer'])->wherePostId($comment->post_id);
        switch($this->type){
            case "appendNext":
                if($comment->level1==0){
                    $where->where('level0','>',$comment->level0)->where('level1','=',0);
                }else{
                    $where->where('level0','=',$comment->level0)->where('level1','>',$comment->level1);
                }
                $where->orderBy('level0')->orderBy('level1')->limit(4);
                break;
            case "append":
                if($comment->level1==0){
                    $where->where('level0','<',$comment->level0)->where('level1','=',0);
                }else{
                    $where->where('level0','=',$comment->level0)->where('level1','<',$comment->level1);
                }
                $where->orderBy('level0', 'desc')->orderBy('level1', 'desc')->limit(4);
                break;
            case "appendNextReplies":
                if($comment->level1==0){
                    $where->where('level0','=',$comment->level0)->where('level1','>',0);
                }else{
                    $where->where('level0','=',$comment->level0)->where('level1','>',$comment->level1);
                }
                $where->orderBy('level0')->orderBy('level1')->limit(4);
                break;
        }
        $comments = $where->get();
        foreach($comments as $comment){
            $comment->extends($this->user);
        }
        return $comments;
    }
    public function extends($user=null){
        $this->customer->getAvatar();
        $likes = Like::whereActivityId($this->activity_id)->get();
        $this->likesCount = $likes->count();
        $this->like=false;
        if($this->level1 == 0){
            $children = Comment::whereParentActivityId($this->activity_id)->get();
            $this->children = [];
            $this->nextChildrenCount = $children->count();
        }
        if(isset($user->customer)){
            $this->getLike($user);
        }
    }
    public function getLike($user){
        $like = Like::whereActivityId($this->activity_id)->whereCustomerId($user->customer->id)->first();
        $this->like = $like?true:false;    
    }
    /**
     * search only comment(not reply) with level1=0
     * $condition
     *   from_id,
     *   to_id, 
     */
    public static function findByCondition($condition,$user){
        $fromComment = self::find($condition['from_id']);
        $toComment = self::find($condition['to_id']);
        $newCondition = ['post_id'=>$fromComment?$fromComment->post_id:$toComment->post_id,'from_level0'=>$fromComment?$fromComment->level0:$toComment->level0,'to_level0'=>$toComment->level0];
        return self::findByConditionWith($newCondition, $user);
    }
    /**
     * search only comment(not reply) with level1=0
     * $condition
     *   from_level0,
     *   to_level0,
     *   post_id
     */
    public static function findByConditionWith($condition,$user){
        $previousComments = Comment::wherePostId($condition['post_id'])->where('level0','<',$condition['from_level0'])->where('level1',0)->get();
        $viewComments = Comment::wherePostId($condition['post_id'])->
            where('level0','>=',$condition['from_level0'])->where('level1',0)->
            where('level0','<=',$condition['to_level0'])->where('level1',0)->
            orderBy('level0')->orderBy('level1')->get();
        foreach($viewComments as $index=>$comment){
            $comment->extends($user);
        }    
        $nextComments = Comment::wherePostId($condition['post_id'])->where('level0','>',$condition['to_level0'])->where('level1',0)->get();
        return [$previousComments, $viewComments, $nextComments];
    }
    /**
     * search only comment(not reply) with level1=0
     * $condition
     *   to_level0,
     *   to_level1,
     *   post_d
     */
    public static function findRepliesByCondition($condition,$user){
        $children = Comment::with('customer')->wherePostId($condition['post_id'])->where('level0',$condition['to_level0'])
            ->where('level1','>',0)->where('level1','<=',$condition['to_level1'])
            ->orderby('level1')->get();
        foreach($children as $comment){
            $comment->extends($user);
        }
        $nextChildrenCount = Comment::wherePostId($condition['post_id'])->where('level0',$condition['to_level0'])
            ->where('level1','>',$condition['to_level1'])->orderby('level1')->count();
        return [$children, $nextChildrenCount];
    }
}
