<?php

namespace App\Observers;

use App\Models\Evento;
use App\Models\Activity;
use App\Models\Post;

class EventoObserver
{
    /**
     * Handle the evento "created" event.
     * should consider address
     *
     * @param  \App\Evento  $evento
     * @return void
     */
    public function created(Evento $evento)
    {
        $activity = new Activity;
        $activity->save();
        $post = new Post;
        $post->fill(['activity_id'=>$activity->id,'customer_id'=>0,'type'=>'evento','object_id'=>$evento->id]);
        Post::withoutEvents(function () use ($post) {
            $post->status = 1;
            $post->save();
        });
    }
}
