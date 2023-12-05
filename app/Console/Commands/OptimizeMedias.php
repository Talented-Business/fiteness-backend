<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;
use App\User;

class OptimizeMedias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimize:medias';

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
        // $this->medias();
        $this->avatars();
    }
    private function avatars(){
        $allFiles = Storage::disk('public')->files('media/user');
        $files = array_filter($allFiles, function ($filename)  {
            $media = User::whereAvatar($filename)->first();
            return $media?false:true;
        });        
        $subFiles = array_filter($allFiles, function ($filename) use ($files) {
            $contain = false;
            foreach($files as $file){
                if($file === $filename){
                    $contain = true;
                    break;
                }
            }
            return $contain;
        });
        // // print_r($subFiles);        
        foreach($subFiles as $file){
            if(Storage::disk('public')->exists($file)) Storage::disk('public')->delete($file);
            $data = pathinfo($file);
            $avatarFile = $data['dirname']."/avatar/".$data['filename'].".".$data['extension'];
            if(Storage::disk('public')->exists($avatarFile)) Storage::disk('public')->delete($avatarFile);            
        }
    }
    private function medias(){
        $allFiles = Storage::disk('s3')->allFiles('social/medias');
        $files = array_filter($allFiles, function ($filename)  {
            return strpos($filename,'-')===false;
        });        
        $files = array_filter($files, function ($filename)  {
            $media = Media::whereSrc($filename)->first();
            return $media?false:true;
        });        
        $subFiles = array_filter($allFiles, function ($filename) use ($files) {
            $contain = false;
            foreach($files as $file){
                if($file === $filename){
                    $contain = true;
                    break;
                }else{
                    $data = pathinfo($file);
                    $name = $data['dirname']."/".$data['filename'].'-';
                    if(str_starts_with($filename, $name)){
                        $contain = true;
                        break;
                    }
                }
            }
            return $contain;
        });
        // print_r($subFiles);        
        foreach($subFiles as $file){
            Storage::disk('s3')->delete($file);
        }
    }
}
