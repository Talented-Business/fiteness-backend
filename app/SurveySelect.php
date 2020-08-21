<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class SurveySelect extends Model
{
    public $timestamps = false;
    protected $table = 'survey_item_selects';
    protected $fillable = ['survey_item_id','option_label'];
    private static $searchableColumns = ['survey_id'];
    public function item(){
        return $this->belongsTo('App\SuveyItem');
    }
}
