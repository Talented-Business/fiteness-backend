<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;

class Activity extends Model
{
    protected $table = 'activities';
    public $timestamps = false;
    protected $fillable = ['customer_id','done_date'];
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
}
