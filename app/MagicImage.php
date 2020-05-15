<?php
namespace App;

trait MagicImage {
    public function cropImage($sourcePath, $thumbSize=200, $destination = null) {

        $parts = explode('.', $sourcePath);
        $ext = $parts[count($parts) - 1];
        if ($ext == 'jpg' || $ext == 'jpeg') {
          $format = 'jpg';
        } else {
          $format = 'png';
        }
      
        if ($format == 'jpg') {
          $sourceImage = imagecreatefromjpeg($sourcePath);
        }
        if ($format == 'png') {
          $sourceImage = imagecreatefrompng($sourcePath);
        }
      
        list($srcWidth, $srcHeight) = getimagesize($sourcePath);
      
        // calculating the part of the image to use for thumbnail
        if ($srcWidth > $srcHeight) {
          $y = 0;
          $x = ($srcWidth - $srcHeight) / 2;
          $smallestSide = $srcHeight;
        } else {
          $x = 0;
          $y = ($srcHeight - $srcWidth) / 2;
          $smallestSide = $srcWidth;
        }
      
        $destinationImage = imagecreatetruecolor($thumbSize, $thumbSize);
        imagecopyresampled($destinationImage, $sourceImage, 0, 0, $x, $y, $thumbSize, $thumbSize, $smallestSide, $smallestSide);
      
        if ($destination == null) {
          header('Content-Type: image/jpeg');
          if ($format == 'jpg') {
            imagejpeg($destinationImage, null, 100);
          }
          if ($format == 'png') {
            imagejpeg($destinationImage);
          }
          if ($destination = null) {
          }
        } else {
          if ($format == 'jpg') {
            imagejpeg($destinationImage, $destination, 100);
          }
          if ($format == 'png') {
            imagepng($destinationImage, $destination);
          }
        }
    }
}