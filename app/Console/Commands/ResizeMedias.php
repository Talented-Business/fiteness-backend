<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Media;

class ResizeMedias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resize:medias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $medias = Media::whereType('image')
            //->whereIn('id',[130])
            ->get();
        foreach($medias as $media){
            // $this->oldDeletes($media);
            $this->makeNewImages($media);
            gc_collect_cycles();
        }
    }
    private function makeNewCroppedImage($image, $size, $id){
        $fileExtension = $image->extension();
        $resizeImg = Media::makeCroppedImage($image, $size);
        $resizeImg->save('storage/app/files/'. $id . '-' . $size[0] . 'X' . $size[1] . '.' . $fileExtension);
    }
    private function makeNewResizedImage($image, $size, $id){
        $fileExtension = $image->extension();
        $resizeImg = Media::makeResizedImage($image, $size);
        $resizeImg->save('storage/app/files/'. $id . '-' . $size . '.' . $fileExtension);
    }
    private function makeNewImages($media){
        $sizes = \App\Setting::convertSizes();
        //download from s3 into local
        $s3File = Storage::disk('s3')->get($media->src);
        $local = Storage::disk('local');
        $local->put('files/'.$media->id, $s3File);
        $image = new File(storage_path('app/files/' . $media->id));        
        $fileExtension = $image->extension();
        $src = substr($media->src,0,strlen($media->src)-strlen($fileExtension) -1 );
        foreach($sizes as $size){
            if (Storage::disk('s3')->exists($src . '-' . $size[0] . 'X' . $size[1] . '.' . $fileExtension)==false) {
                $this->makeNewCroppedImage($image, $size, $media->id);
                Storage::disk('s3')->putFileAs(
                    '/',
                    new File(storage_path('app/files/' . $media->id . '-' . $size[0] . 'X' . $size[1] . '.' . $fileExtension)),
                    $src . '-' . $size[0] . 'X' . $size[1] . '.' . $fileExtension
                );    
                Storage::disk('local')->delete('files/'.$media->id . '-' . $size[0] . 'X' . $size[1] . '.' . $fileExtension);
            }
            if (Storage::disk('s3')->exists($src . '-' . $size[0] . '.' . $fileExtension)==false) {
                $this->makeNewResizedImage($image, $size[0], $media->id);                
                Storage::disk('s3')->putFileAs(
                    '/',
                    new File(storage_path('app/files/' . $media->id . '-' . $size[0] . '.' . $fileExtension)),
                    $src . '-' . $size[0] . '.' . $fileExtension
                );    
                Storage::disk('local')->delete('files/'.$media->id . '-' . $size[0] . '.' . $fileExtension);
            }
        }
        Storage::disk('local')->delete('files/'.$media->id);
    }
    private function oldDeletes($media){
        // Storage::disk('s3')->delete('path/file.jpg');
    }
}
