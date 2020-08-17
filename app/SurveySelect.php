<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class SurveySelect extends Model
{
    protected $table = 'survey_item_selects';
    protected $fillable = ['question','label','survey_id'];
    private static $searchableColumns = ['survey_id'];
    public function item(){
        return $this->belongsTo('App\SuveyItem');
    }
}
