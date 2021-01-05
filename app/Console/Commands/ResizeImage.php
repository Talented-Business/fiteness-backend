<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Event;

class ResizeImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resize:image';

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
        $this->resizeEvent();
    }
    private function resizeEvent(){
        $files = \Storage::disk('public')->files('photos');
        $convertFiles = [];
        foreach($files as $file){
            $key = basename($file);
            $convertFiles[$key] = $file;
        }
        $mfiles = \Storage::disk('public')->files('photos/m');
        $convertmFiles = [];
        foreach($mfiles as $file){
            $key = basename($file);
            $convertmFiles[$key] = $file;
            if(isset($convertFiles[$key])){
                unset($convertFiles[$key]);   
            }
        }
        print_r($convertFiles);
    }
}
