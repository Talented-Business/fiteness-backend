<?php

namespace App\Observers;

use App\Models\Comment;

class CommentObserver
{
    /**
     * Handle the comment "created" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        $pattern = '/@\[(.+?)\]\(([0-9]+)\)/';
        $lines = explode("\n",$comment->content);
        $ids = [];
        foreach( $lines as $line){
            preg_match_all($pattern, $line, $matches);
            // print_r($matches);
            if(count($matches)>0){
                // foreach($matches[0] as $index=>$search){
                //     $line = str_replace($search,$matches[1][$index],$line);
                //     $jsonLine = str_replace($search,"$".$matches[2][$index]."$",$jsonLine);
                // }
                $ids = array_merge($ids,$matches[2]);
            }
        }
        foreach($ids as $id){
            \App\Models\Notification::mentionOnComment($id, $comment->customer_id, $comment->post);
        }
    }
}
