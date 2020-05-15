<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $table="subscription_plans";
    public function service(){
        return $this->belongsTo('App\Service','service_id');
    }    
}
