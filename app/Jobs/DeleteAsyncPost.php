<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\Media;

class DeleteAsyncPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $medias = Media::wherePostId($this->id)->get();
        foreach($medias as $media){
            // $media->alt_text = "a";
            // $media->save();
            \Storage::disk('s3')->delete($media->src);
            $media->delete();
        }
        $post = Post::find($this->id);
        $post->delete();
    }
}
