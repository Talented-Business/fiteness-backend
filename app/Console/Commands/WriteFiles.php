<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class WriteFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'write:files';

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
        // $userInfo = posix_getpwuid(posix_getuid());
        // $config = new \App\Config;
        // $config->updateConfig('queue_user_1', json_encode($userInfo));
        // print_r($userInfo);
        $fileName = 'files/EiDsSoFwdcmFhdGcBpELwbUhFeywinYmU4eQp6Gd.png';
        $file = new File(storage_path('app/' . $fileName));
        Storage::disk('local')->putFileAs(
            '/',
            $file,
            'files/1.png'
        );
        $sizes = \App\Setting::convertSizes();
        $fileExtension = $file->extension();
        for($i = 0; $i < count($sizes); $i++) {
            $size = $sizes[$i];
            $resizeImg = \App\Models\Media::makeCroppedImage($file, $size);
            $resizeImg->save(storage_path('app/files/'. 1 . '-' . $size[0] . 'X' . $size[1] . '.' . $fileExtension));
        }    
    }
}
