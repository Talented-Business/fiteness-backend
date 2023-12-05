<?php

namespace App\Observers;

use App\Models\Post;
use App\Config;

class PostObserver
{
    /**
     * Handle the post "saved" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function saved(Post $post)
    {
        Post::withoutEvents(function () use ($post) {
            // $pattern = '/\@\{\$([^$]+)\$\}\(([0-9]+)\)/';
            $pattern = '/@\[(.+?)\]\(([0-9]+)\)/';
            $lines = explode("\n",$post->content);
            $texts = [];
            $jsons = [];
            $ids = [];
            foreach( $lines as $line){
                preg_match_all($pattern, $line, $matches);
                // print_r($matches);
                $jsonLine = $line;
                if(count($matches)>0){
                    foreach($matches[0] as $index=>$search){
                        $line = str_replace($search,$matches[1][$index],$line);
                        $jsonLine = str_replace($search,"$".$matches[2][$index]."$",$jsonLine);
                    }
                    $jsons[] = ['content'=>$jsonLine,'ids'=>$matches[2]];
                    $ids = array_merge($ids,$matches[2]);
                }else{
                    $jsons[] = ['content'=>$jsonLine];
                }
                $texts[] = $line;
            }
            $post->searchable_content = implode("\n",$texts);
            $post->json_content = $jsons;
            $post->save();
            foreach($ids as $id){
                \App\Models\Notification::mentionOnPost($id, $post->customer_id, $post);
            }    
            foreach($post->tag_followers as $id){
                \App\Models\Notification::tagOn($id, $post->customer_id, $post);
            }    
        });
    }
}
