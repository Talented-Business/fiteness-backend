<?php

namespace App\Observers;

use App\Event;
use App\Models\Activity;
use App\Models\Post;

class EventObserver
{
    /**
     * Handle the event "created" event.
     *
     * @param  \App\Event  $event
     * @return void
     */
    public function created(Event $event)
    {
        $activity = new Activity;
        $activity->save();
        $post = new Post;
        $post->fill(['activity_id'=>$activity->id,'customer_id'=>0,'type'=>'blog','object_id'=>$event->id]);
        Post::withoutEvents(function () use ($post) {
            $post->status = 1;
            $post->save();
        });
    }
}
