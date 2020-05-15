<?php

use Illuminate\Database\Seeder;
use App\Cart;


class CartTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $frequencies = ['monthly', 'quarterly', 'semiannual', 'yearly'];
        for($i = 1;$i<10;$i++){
            $item = Cart::firstOrNew(['session_id'=>$i]);
            $item->session_id=$i;
            $customer = rand(0,1);
            $item->customer_id=$customer==0?32:28;//rand(1,100);
            $item->plan_id=2;
            $frequency = rand(0,3);
            $item->frequency=$frequencies[$frequency];
            $out = rand(0,1);
            $item->out = $out==0?'no':time();
            $item->mail = 'no';
            $item->updated_at = "2020-04-12 00:00:00";
            $item->save();
        }
    }
}
