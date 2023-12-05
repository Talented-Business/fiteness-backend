<?php

namespace App\Observers;

use App\Models\WorkoutComment;
use App\Models\Activity;
use App\Models\Post;

class WorkoutCommentObserver
{
    /**
     * Handle the workout comment "created" event.
     *
     * @param  \App\WorkoutComment  $workoutComment
     * @return void
     */
    public function created(WorkoutComment $workoutComment)
    {
        if ($workoutComment->type==='basic'){
            $activity = new Activity;
            $activity->save();
            $post = new Post;
            $post->fill(['activity_id'=>$activity->id,'customer_id'=>$workoutComment->customer_id,'type'=>'workout','object_id'=>$workoutComment->id]);
            Post::withoutEvents(function () use ($post) {
                $post->status = 1;
                $post->save();
            });
        }
        $customer = \App\Customer::find($workoutComment->customer_id);
    }
}
