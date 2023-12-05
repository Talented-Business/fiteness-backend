<?php

namespace App\Observers;

use App\Benchmark;
use App\Models\Activity;
use App\Models\Post;

class BenchmarkObserver
{
    /**
     * Handle the benchmark "created" event.
     * should consider post_date
     *
     * @param  \App\Benchmark  $benchmark
     * @return void
     */
    public function created(Benchmark $benchmark)
    {
        // if($benchmark->status == 'Publish'){
            $activity = new Activity;
            $activity->save();
            $post = new Post;
            $post->fill(['activity_id'=>$activity->id,'customer_id'=>0,'type'=>'benchmark','object_id'=>$benchmark->id]);
            Post::withoutEvents(function () use ($post) {
                $post->status = 1;
                $post->save();
            });
        // }
    }
}
