<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventoComment extends Model
{
    protected $table = 'evento_comments';
    protected $type;
    protected $user;
    protected $fillable = ['customer_id','evento_id','parent_id','level0','level1','content'];
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
    public function evento()
    {
        return $this->belongsTo('App\Models\Evento', 'evento_id');
    }
    public function assignFrontSearch($request){
        $this->id = $request->id;
        $this->type = $request->type;
        $this->user = $request->user();
    }
    /**
     * param id; comment from id or to id
     * param type; appendNext or appendPrevious 
     */
    public function search(){
        $comment = self::find($this->id);
        $where = self::with(['customer'])->whereEventoId($comment->evento_id);
        switch($this->type){
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
        if($this->level1 == 0){
            $children = self::whereParentId($this->id)->get();
            $this->children = [];
            $this->nextChildrenCount = $children->count();
        }
    }
    /**
     * search only comment(not reply) with level1=0
     */
    public static function findByCondition($comment, $user){
        $newCondition = ['evento_id'=>$comment->evento_id];
        return self::findByConditionWith($newCondition, $user);
    }
    /**
     * search only comment(not reply) with level1=0
     * $condition
     *   evento_id
     */
    public static function findByConditionWith($condition,$user){
        $viewComments = self::whereEventoId($condition['evento_id'])->
            where('level1',0)->
        orderBy('level0')->orderBy('level1')->get();
        foreach($viewComments as $index=>$comment){
            $comment->extends($user);
        }    
        return $viewComments;
    }
    /**
     * search only comment(not reply) with level1=0
     * $condition
     *   to_level0,
     *   to_level1,
     *   evento_id
     */
    public static function findRepliesByCondition($condition,$user){
        $children = self::with('customer')->whereEventoId($condition['evento_id'])->where('level0',$condition['to_level0'])
            ->where('level1','>',0)->where('level1','<=',$condition['to_level1'])
            ->orderby('level1')->get();
        foreach($children as $comment){
            $comment->extends($user);
        }
        $nextChildrenCount = self::whereEventoId($condition['evento_id'])->where('level0',$condition['to_level0'])
            ->where('level1','>',$condition['to_level1'])->orderby('level1')->count();
        return [$children, $nextChildrenCount];
    }
}