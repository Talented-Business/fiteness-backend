<?php

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class CommentSampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customerIds = [5882, 46, 60,3116,108];
        $post_id = 29;
        $customerId = $customerIds[rand(0,count($customerIds)-1)];
        $faker = \Faker\Factory::create();
        $activity = new Activity;
        $activity->save();
        $parentActivityId = 146;
        if(isset($parentActivityId)){
            $comment = Comment::whereActivityId($parentActivityId)->first();
            if($comment){
                if($comment->level1 == 0){
                    $parentActivityId = $comment->activity_id;
                    $maxlevel = $comment->level0;
                }else{
                    $comment = Comment::whereActivityId($comment->parent_activity_id)->first();
                    $parentActivityId = $comment->activity_id;
                    $maxlevel = $comment->level0;
                }
                $maxlevel1 = DB::table("comments")->where('parent_activity_id',$parentActivityId)->max('level1');
                if($maxlevel == null)$maxlevel = 0;
                $maxlevel1++;
            }
        }else{
            $maxlevel = DB::table("comments")->where('post_id',$post_id)->max('level0');
            if($maxlevel == null)$maxlevel = 0;
            $post = Post::find($post_id);
            $parentActivityId = $post->activity_id;
            $maxlevel++;
            $maxlevel1 = 0;
        }
        $comment = Comment::create([
            'activity_id'=>$activity->id,
            'customer_id'=>$customerId,
            'content'=>$faker->realText(500),
            'post_id'=>$post_id,
            'parent_activity_id'=>$parentActivityId,
            'level0'=>$maxlevel,
            'level1'=>$maxlevel1,
            ]);
        $comment->save();
        Post::withoutEvents(function () use ($comment) {
            $comment->post->touch();
        });
    }
}
