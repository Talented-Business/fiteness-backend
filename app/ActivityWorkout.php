<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;

class ActivityWorkout extends Model
{
    protected $table = 'workout_activities';
    public $timestamps = false;
    protected $fillable = ['customer_id','publish_date','done_date','type'];
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
}
