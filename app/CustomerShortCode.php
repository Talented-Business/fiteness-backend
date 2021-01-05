<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerShortcode extends Model
{
    protected $fillable = ['customer_id','shortcode_id','alternate_id'];
    public function customer(){
        return $this->belongsTo('App\Customer');
    }
    public function shortcode(){
        return $this->belongsTo('App\ShortCode');
    }
    public function alternate(){
        return $this->belongsTo('App\ShortCode','alternate_id');
    }
}