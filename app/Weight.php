<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Weight extends Model
{
    protected $fillable = ['title','description'];    
    public static function validateRules(){
        return array(
        );
    }
    private static $searchableColumns = [];
    public function customer(){
        return $this->belongsTo('App\Customer');
    }
    public static function convert($value,$unit){
        if($unit == 'lbs'){
            $value *= 0.453592;
        }
        return $value;
    }
    public function search(){
    }
}
