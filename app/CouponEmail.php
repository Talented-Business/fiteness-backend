<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Mail;

class CouponEmail extends Model
{
    const COUPON_URL = '/pricing?coupon=';
    const REFERRAL_URL = '/signup?referral=';
    const DEFAULT = null;
    const REFERRAL_NAME = 'referral';
    protected $fillable = ['coupon_id','email'];
    public function coupon(){
        return $this->belongsTo('App\Coupon');
    }
}