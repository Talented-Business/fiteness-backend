<?php

namespace App;
use Intervention\Image\Facades\Image;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class ProductImage extends Model
{
    protected $table='product_gallery';
    protected $fillable = ['product_id','image']; 
    public function getImageSize($logo,$size) 
    {
        $image =  implode('-' . Setting::IMAGE_SIZES[$size] . '.', explode('.', $logo));
        $imagePath = url('storage/'.$image);
        return $imagePath;
    }
    public function resizeImage($photoPath,$imageGalleryId,$fileExtension,$fileNameUpdate)
    {   
        $sizes = Setting::convertSizes();
        for($i = 0; $i < count($sizes); $i++) {
            $size = $sizes[$i];
            $resizeImg = Image::make('storage/' . $photoPath.'/'.$fileNameUpdate);
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
            });
            $resizeImg->save('storage/' . $photoPath .'/'. $imageGalleryId . '-' . $size[0] . 'X' . $size[1] . '.' . $fileExtension);
        }
    }
}
