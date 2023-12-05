<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Owenoj\LaravelGetId3\GetId3;
use App\Models\Media;

class updateVideosWidth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:width';

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
        // $track = GetId3::fromDiskAndPath('s3', '/some/file.mp3');
        $medias = Media::whereType('video')->get();
        foreach($medias as $media){
            $track = GetId3::fromDiskAndPath('s3', $media->src);
            $data = $track->extractInfo();
            if(isset($data['video']['resolution_x'])){
                if($data['video']['rotate'] === 90){
                    $media->height = $data['video']['resolution_x'];
                    $media->width = $data['video']['resolution_y'];
                }else{
                    $media->width = $data['video']['resolution_x'];
                    $media->height = $data['video']['resolution_y'];
                }
                $media->save();
            }
        }
        // $media = Media::find(93);
        // if($media){
        //     $track = GetId3::fromDiskAndPath('s3', $media->src);
        //     $data = $track->extractInfo();
        //     if(isset($data['video']['resolution_x'])){
        //         if($data['video']['rotate'] === 90){
        //             $media->height = $data['video']['resolution_x'];
        //             $media->width = $data['video']['resolution_y'];
        //         }else{
        //             $media->width = $data['video']['resolution_x'];
        //             $media->height = $data['video']['resolution_y'];
        //         }
        //         $media->save();
        //     }
        // }
    }
}
