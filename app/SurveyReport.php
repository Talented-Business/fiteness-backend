<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class SurveyReport extends Model
{
    protected $fillable = ['question','label','survey_id'];
    private static $searchableColumns = ['survey_id'];
    public function item(){
        return $this->belongsTo('App\SuveyItem');
    }
    public function customer(){
        return $this->belongsTo('App\Customer');
    }
    public static function validateRules($id=null){
        return array(
            'suryey_id'=>'numeric',
            'question'=>'required',
            'label'=>'required',
        );
    }
    public function assignSearch($request){
        foreach(self::$searchableColumns as $property){
            $this->{$property} = $request->input($property);
        }
    }
    public function searchAll(){
        $where = SurveyItem::whereRaw('1');
        if($this->survey_id!=null){
            $where->whereSurveyId($this->survey_id);
        }
        return $where->get();
    }

}
