<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManagerStatic as Image;
// use App\Jobs\ImageResizing;
// use App\Jobs\ImageDelete;
use App\Jobs\MoveFileToS3;


class Media extends Model
{
    protected $table = 'medias';
    protected $fillable = ['user_id','title','body','status'];
    const IMAGE_SIZES = [            
        'x-small' => '50X50', 
        'small' => '150X150', 
        // 'medium' => '250X250',
        // 'medium-ad' => '283X226', 
        // 'large' => '400X400', 
        // 'large-ad' => '663X434', 
        'x-large' => '700X700', 
        // 'xx-large' => '900X900', 
    ];
    public static function convertSizes(){
        $sizes = array_values(self::IMAGE_SIZES);
        $y = [];
        foreach($sizes as $size){
            $s = explode('X',$size);
            $y[] = $s;
        }
        return $y;
    }
    public function post(){
        return $this->belongsTo("App\Models\Post");
    }
    public static function validateRules($id=null){
        return array(
            'user_id'=>'required|max:255',
            'title'=>'required|max:255',
        );
    }
    public function upload($request,$name,$attachment=null){
        $this->uploadSingle($request->file($name),$attachment);
    }
    public function uploadSingle($image,$attachment=null){
        $fileExtension = $image->extension();
        $year = date("Y");
        $month = date("m");
        if($this->id == null){
            $this->mime_type=$image->getMimeType();
            if(strstr($this->mime_type, "video/")){
                $this->type='video';
            }else if(strstr($this->mime_type, "image/")){
                $this->type='image';
            }else{
                $this->type='file';
            }
            if($attachment)$this->attachment = $attachment;
            $this->src = '';
            $this->save();
        }
        $filename = \Storage::disk('local')->putFile('files', $image);
        $photoPath = 'social/medias/'.$year.'/'.$month;
        $this->src = $photoPath.'/'.$this->id.'.'.$fileExtension;
        // $path = $image->storeAs('', #$path
        // $this->src, #$fileName
        // ['disk'=>'s3', 'visibility'=>'public']);
        // $this->url = \Storage::disk('s3')->url($path);
        $this->save();
        if(PHP_OS == 'Linux'){
            $file = storage_path('app/' . $filename);
            $output = shell_exec("mogrify -auto-orient $file");
            sleep(1);
        }
        MoveFileToS3::dispatch($this->id, $filename);
        if($this->type == 'image'){
            //dispatch(new ImageResizing($this->id));
        }
    }
    public function resizeImage($image)
    {   
        $sizes = self::convertSizes();
        $fileExtension = $image->extension();
        $extension = $image->getClientOriginalExtension();
        $year = date("Y");
        $month = date("m");
        for($i = 0; $i < count($sizes); $i++) {
            $size = $sizes[$i];
            $resizeImg = Image::make($image);
            if($size[0]==$size[1]){
                $height = $resizeImg->height();
                $width = $resizeImg->width();
                if($width > $height) {
                    $cropStartPointX = round(($width - $height) / 2);
                    $cropStartPointY = 0;
                    $cropWidth = $height;
                    $cropHeight = $height;
                }
                else {
                    $cropStartPointX = 0;
                    $cropStartPointY = round(($height - $width) / 2);
                    $cropWidth = $width;
                    $cropHeight = $width;
                }
            }
            else{
                if($size[0] > $size[1]){
                    $height = $resizeImg->height();
                    $width = $resizeImg->width();
                    if($width > $height) {
                        $sizeRate = $size[0]/$size[1];
                        $cropStartPointX = round(($width - ($height*$sizeRate)) / 2);
                        $cropStartPointY = 0;
                        $cropWidth = round($height*$sizeRate);
                        $cropHeight = $height;
                    }
                    else {
                        $sizeRate = $size[1]/$size[0];
                        $cropStartPointX = 0;
                        $cropStartPointY = round(($height - ($width*$sizeRate)) / 2);
                        $cropWidth = $width;
                        $cropHeight = round($width*$sizeRate);
                    }
                }
            }
            
            $resizeImg->crop($cropWidth, $cropHeight, $cropStartPointX, $cropStartPointY)
            ->resize($size[0], $size[1], function($constraint) {
                $constraint->aspectRatio();
            })->encode($extension);
            if(env('FILE_UPLOAD') == 'local'){
                $resizeImg->save('storage/' . $photoPath .'/'. $this->id . '-' . $size[0] . 'X' . $size[1] . '.' . $fileExtension);
            }else{
                $src = 'marketplace/media/'.$year.'/'.$month.'/'. $this->id . '-' . $size[0] . 'X' . $size[1] . '.' . $fileExtension;
                \Storage::disk('s3')->put($src, (string)$resizeImg, 'public');
                // $resizeImg->storeAs('', #$path
                //     $src, #$fileName
                //     ['disk'=>'s3', 'visibility'=>'public']);
            }
        }
    }
    public function getImagePath($size,$timestap = false) 
    {        
        $imagePath = $this->url;
        if($timestap)$imagePath.="?".time();
        return $imagePath;
    }
    public function asyncDelete(){
        // dispatch(new ImageDelete($this->id));
    }
    public function removeFiles(){
        \Storage::disk('s3')->delete($this->src);
    }
    public static function makeCroppedImage($image, $size){
        $fileExtension = $image->extension();
        $resizeImg = Image::make($image);
        if($size[0]==$size[1]){
            $height = $resizeImg->height();
            $width = $resizeImg->width();
            if($width > $height) {
                $cropStartPointX = round(($width - $height) / 2);
                $cropStartPointY = 0;
                $cropWidth = $height;
                $cropHeight = $height;
            }
            else {
                $cropStartPointX = 0;
                $cropStartPointY = round(($height - $width) / 2);
                $cropWidth = $width;
                $cropHeight = $width;
            }
        }
        else{
            if($size[0] > $size[1]){
                $height = $resizeImg->height();
                $width = $resizeImg->width();
                if($width > $height) {
                    $sizeRate = $size[0]/$size[1];
                    $cropStartPointX = round(($width - ($height*$sizeRate)) / 2);
                    $cropStartPointY = 0;
                    $cropWidth = round($height*$sizeRate);
                    $cropHeight = $height;
                }
                else {
                    $sizeRate = $size[1]/$size[0];
                    $cropStartPointX = 0;
                    $cropStartPointY = round(($height - ($width*$sizeRate)) / 2);
                    $cropWidth = $width;
                    $cropHeight = round($width*$sizeRate);
                }
            }
        }
        
        $resizeImg->crop($cropWidth, $cropHeight, $cropStartPointX, $cropStartPointY)
        ->resize($size[0], $size[1], function($constraint) {
            $constraint->aspectRatio();
        })->encode($fileExtension);
        return $resizeImg;
    }
    public static function makeResizedImage($image, $size){
        $fileExtension = $image->extension();
        $resizeImg = Image::make($image);
        $height = $resizeImg->height();
        $width = $resizeImg->width();
        if($width > $height) {
            $sizeRate = $size/$width;
            $resizeWidth = $width;
            $resizeHeight = round($height * $sizeRate);
        }
        else {
            $sizeRate = $size/$height;
            $resizeWidth = round($width * $sizeRate);
            $resizeHeight = $height;
        }
        
        $resizeImg->resize($resizeWidth, $resizeHeight, function($constraint) {
            $constraint->aspectRatio();
        })->encode($fileExtension);
        return $resizeImg;
    }
}