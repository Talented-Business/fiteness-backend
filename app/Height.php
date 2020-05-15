<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Height extends Model
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
        if($unit == 'in'){
            $value *= 2.54;
        }
        return $value;
    }
    public function search(){
    }
}
