<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = 'records';
    public $timestamps = false;
    protected $fillable = [];    
    public static function validateRules(){
        return array(
        );
    }
    public function customer(){
        return $this->belongsTo('App\Customer','customer_id');
    }
}
