<?php

namespace App\Observers;

use App\Company;
use App\Models\Activity;
use App\Models\Post;

class CompanyObserver
{
    /**
     * Handle the company "created" event.
     * should consider address and country
     *
     * @param  \App\Company  $company
     * @return void
     */
    public function created(Company $company)
    {
        $activity = new Activity;
        $activity->save();
        $post = new Post;
        $post->fill(['activity_id'=>$activity->id,'customer_id'=>0,'type'=>'shop','object_id'=>$company->id]);
        Post::withoutEvents(function () use ($post) {
            $post->status = 1;
            $post->save();
        });
    }
}
