<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class WorkoutComment extends Model
{
    protected $table = 'workout_comments';
    protected $type;
    protected $user;
    protected $fillable = ['customer_id','publish_date','content','dumbells_weight','type','workout'];
    public static function validateRules($id=null){
        if ($id) return array(
            'content'=>'required',
            'dumbells_weight'=>'numeric | min:0 | nullable'
        );
        return array(
            'content'=>'required',
            'publish_date'=>'required | date_format:Y-m-d',
            'dumbells_weight'=>'numeric | min:0 | nullable',
            'workout'=>'required',
            'type'=>[Rule::in(['basic', 'extra'])],
        );
    }
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
}