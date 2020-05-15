<?php

use Illuminate\Database\Seeder;
use App\Category;
use App\Event;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<20;$i++){
            $category = new Category;
            $category->name="Category ".$i;
            $category->slug="Category ".$i;
            $category->save();
        }
        for($i=0;$i<450;$i++){
            $event = new Event;
            $event->title="Event ".$i;
            $event->description="Category ".$i;
            $event->category_id = rand(1,20);
            $event->image = "image";
            $event->save();
        }
    }
}
