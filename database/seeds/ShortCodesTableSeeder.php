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
        $shortcodes = Shortcode::all();
        $faker = Faker\Factory::create(); 
        foreach($shortcodes as $shortcode){
            // $shortcode->time = rand(1,4) * 10;
            // $shortcode->level = rand(1,5);
            // $alternateA = $shortcodes[rand(0,$shortcodes->count()-1)];
            // if($alternateA->id!=$shortcode->id){
            //     $shortcode->alternate_a = $alternateA->id;
            //     $shortcode->multipler_a = rand(2,6) /2;    
            // }
            // $alternateB = $shortcodes[rand(0,$shortcodes->count()-1)];
            // if($alternateB->id!=$shortcode->id){
            //     $shortcode->alternate_b = $alternateB->id;
            //     $shortcode->multipler_b = rand(2,6) /2;
            // }
            // $shortcode->instruction =$faker->realText(500);
            // $shortcode->save();
            if($shortcode->alternate_a == $shortcode->alternate_b){
                print_r($shortcode->id);print_r("\n");
                print_r($shortcode->alternate_a);print_r("\n");
                // $alternateA = $shortcodes[rand(0,$shortcodes->count()-1)];
                // if($alternateA->id!=$shortcode->id){
                //     $shortcode->alternate_a = $alternateA->id;
                //     $shortcode->multipler_a = rand(2,6) /2;    
                // }
                // $alternateB = $shortcodes[rand(0,$shortcodes->count()-1)];
                // if($alternateB->id!=$shortcode->id){
                //     $shortcode->alternate_b = $alternateB->id;
                //     $shortcode->multipler_b = rand(2,6) /2;
                // }
                // $shortcode->save();
            }
        }
    }
}
