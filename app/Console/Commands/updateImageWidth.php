<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Media;


class updateImageWidth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:width';

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
        $medias = Media::whereType('image')->get();
        foreach($medias as $media){
            $data = getimagesize($media->url);
            if(isset($data[0])){
                $media->width = $data[0];
                $media->height = $data[1];
                $media->save();
            }
            print_r($media->id);
        }
    }
}
