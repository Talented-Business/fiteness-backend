<?php

use Illuminate\Database\Seeder;
use App\Coupon;

class CouponsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0;$i<450;$i++){
            $coupon = new Coupon;
            $coupon->code="code".$i;
            $coupon->name = 'Coupon owner name '.$i;
            $mail = rand(0,1);
            if($mail==1)$coupon->mail="admin$i@gmail.com";
            $amount = 3.66 + rand(0,3);
            $coupon->discount=$amount;
            $coupon->renewal=rand(0,1);
            $states = ['Active','Disabled'];
            $coupon->status=$states[rand(0,1)];
            $j = rand(10,30);
            $coupon->created_at = "2019-12-$j 00:00:00";
            $coupon->save();
        }
    }
}
