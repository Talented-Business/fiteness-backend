<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Done extends Model
{
    protected $table = 'done_workouts';
    protected $fillable = ['customer_id','done_date'];
    public static function validateRules(){
        return array(
            'customer_id'=>'required',
            'done_date'=>'required',
        );
    }
    public function assign($request){
        foreach($this->fillable as $property){
            $this->{$property} = $request->input($property);
        }
    }
}
