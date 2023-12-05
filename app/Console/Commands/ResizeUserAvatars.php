<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Media;
use App\User;

class ResizeUserAvatars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resize:avatars';

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
        $users = User::whereNotNull('avatar')->get();
        foreach($users as $user){
            $this->makeNewImages($user);
            gc_collect_cycles();
        }
    }
    private function makeNewImages($user){
        $size = [60, 60];
        $avatarFile = Storage::disk('public')->get($user->avatar);
        $image = new File(storage_path('app/public/' . $user->avatar));        
        $data = pathinfo($user->avatar);
        $avatarFile = $data['dirname']."/avatar/".$data['filename'].".".$data['extension'];
        if (Storage::disk('public')->exists($avatarFile)==false) {
            try{
                $resizeImg = Media::makeCroppedImage($image, $size);
                $resizeImg->save('storage/app/public/'. $avatarFile);
            }catch(\Exception $e){
                print_r($user->id);
                var_dump($e);
                die;
            }
        }
    }
}
