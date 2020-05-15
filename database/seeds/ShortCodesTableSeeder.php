<?php

use Illuminate\Database\Seeder;
use App\Shortcode;

class ShortCodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0;$i<450;$i++){
            $shortcode = new Shortcode;
            $shortcode->name = 'Shortcode '.$i;
            $shortcode->link = 'https://www.bank.name'.$i;
            $states = ['Active','Inactive'];
            $shortcode->status=$states[rand(0,1)];
            $shortcode->save();
        }
    }
}
