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
use Illuminate\Support\Facades\App;
use Owenoj\LaravelGetId3\GetId3;
use App\Models\Media;

class MoveFileToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $filename;
    protected $id;
    public $timeout = 1000;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $filename)
    {
        $this->filename = $filename;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userInfo = posix_getpwuid(posix_getuid());
        $user = $userInfo['name'];
        $config = new \App\Config;
        $config->updateConfig('queue_user_'.$this->id, json_encode($userInfo));
        $config->updateConfig('queue_filename_'.$this->id, $this->filename);
        // Upload file to S3
        // $path = $image->storeAs('', #$path
        // $this->src, #$fileName
        // ['disk'=>'s3', 'visibility'=>'public']);
        // $this->url = \Storage::disk('s3')->url($path);
        $media = Media::find($this->id);
        $file = new File(storage_path('app/' . $this->filename));
        $result = Storage::disk('s3')->putFileAs(
            '/',
            $file,
            $media->src
        );
        $cdnWebsite = "https://s3.fitemos.com/";
        if (App::environment('local')) {
            $cdnWebsite = "https://devs3.fitemos.com/";
        }        
        if (App::environment('staging')) {
            $cdnWebsite = "https://devs3.fitemos.com/";
        }        
        if($media->type=='video'){
            $track = GetId3::fromDiskAndPath('local', $this->filename);
            $data = $track->extractInfo();
            if(isset($data['video']['resolution_x'])){
                if($data['video']['rotate'] === 90){
                    $media->height = $data['video']['resolution_x'];
                    $media->width = $data['video']['resolution_y'];
                }else{
                    $media->width = $data['video']['resolution_x'];
                    $media->height = $data['video']['resolution_y'];
                }
            }
        }
        if($media->type=='image'){
            $data = getimagesize(storage_path('app/' . $this->filename));
            if(isset($data[0])){
                $media->width = $data[0];
                $media->height = $data[1];
            }            
        }
        $media->url = $cdnWebsite.$media->src;
        $media->save();
        // Forces collection of any existing garbage cycles
        // If we don't add this, in some cases the file remains locked
        gc_collect_cycles();
        if($media->type=='image')$this->resizeImage($file, $media->src);

        if ($result == false) {
            throw new Exception("Couldn't upload file to S3");
        }
        // if(unlink(storage_path('app/' . $this->filename))){
        //     throw new Exception('File could not be deleted from the local filesystem '.storage_path('app/' . $this->filename));
        // }
        // delete file from local filesystem
        Storage::disk('local')->delete($this->filename);
        
    }
    public function resizeImage($image, $src)
    {   
        $sizes = \App\Setting::convertSizes();
        $fileExtension = $image->extension();
        $src = substr($src,0,strlen($src)-strlen($fileExtension) -1 );
        ini_set('memory_limit', '256M');
        for($i = 0; $i < count($sizes); $i++) {
            $size = $sizes[$i];
            $resizeImg = Media::makeCroppedImage($image, $size);
            $resizeImg->save(storage_path('app/files/'. $this->id . '-' . $size[0] . 'X' . $size[1] . '.' . $fileExtension));
            $result = Storage::disk('s3')->putFileAs(
                '/',
                new File(storage_path('app/files/' . $this->id . '-' . $size[0] . 'X' . $size[1] . '.' . $fileExtension)),
                $src . '-' . $size[0] . 'X' . $size[1] . '.' . $fileExtension
            );            
            Storage::disk('local')->delete('files/'.$this->id . '-' . $size[0] . 'X' . $size[1] . '.' . $fileExtension);
            $resizeImg = Media::makeResizedImage($image, $size[0]);
            $resizeImg->save(storage_path('app/files/'. $this->id . '-' . $size[0] . '.' . $fileExtension));
            $result = Storage::disk('s3')->putFileAs(
                '/',
                new File(storage_path('app/files/' . $this->id . '-' . $size[0]. '.' . $fileExtension)),
                $src . '-' . $size[0] . '.' . $fileExtension
            );            
            Storage::disk('local')->delete('files/'.$this->id . '-' . $size[0]. '.' . $fileExtension);
        }
    }
}
