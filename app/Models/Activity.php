<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'social_activities';
    public $timestamps = false;
    public function updateLikes(){
        $this->likes = Like::whereActivityId($this->id)->get()->count();            
        $this->save();
        $post = Post::whereActivityId($this->id)->first();
        if($post){
            Post::withoutEvents(function () use ($post) {
                $post->touch();
            });
        }else{
            $comment = Comment::whereActivityId($this->id)->first();
            Post::withoutEvents(function () use ($comment) {
                $comment->post->touch();
            });
        }
    }
}
