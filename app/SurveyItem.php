<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class SurveyItem extends Model
{
    protected $fillable = ['question','label','survey_id'];
    private static $searchableColumns = ['survey_id'];
    public function survey(){
        return $this->belongsTo('App\Survey');
    }
    public function options(){
        return $this->hasMany('App\SurveySelect');
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
