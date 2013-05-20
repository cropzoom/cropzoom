<?php

session_start();
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past 

//Obtains parameters from POST request
$source = $_POST["imageSource"];
$viewPortW = $_POST["viewPortW"];
$viewPortH = $_POST["viewPortH"];
$pWidth = $_POST["imageW"];
$pHeight =  $_POST["imageH"];
$selectorX = $_POST["selectorX"];
$selectorY = $_POST["selectorY"];
$ext = end(explode(".",$_POST["imageSource"]));

//Create the image from the image sent
$img = new Imagick($source);
//Obtain width and height from the original source.
$width = $img->getImageWidth();
$height = $img->getImageHeight();

//resize the image if the width and height doesn't match
if($pWidth != $width && $pHeight != $height){
    $img->resizeImage($pWidth, $pHeight, imagick::FILTER_CATROM, 1, false);
    $width = $img->getImageWidth();
    $height = $img->getImageHeight();
}

//Check if we have to rotate the image
if($_POST["imageRotate"]){
    $angle = $_POST["imageRotate"];
    //rotate the image and set 'transparent' as background of rotation
    $img->rotateImage(new ImagickPixel('none'), $angle);
    $rotated_width = $img->getImageWidth();
    $rotated_height = $img->getImageHeight();

    //obtain the difference between sizes so we can move the x,y points.
    $diffW = abs($rotated_width - $width) / 2;
    $diffH = abs($rotated_height - $height) / 2;

    $_POST["imageX"] = ($rotated_width > $width ? $_POST["imageX"] - $diffW : $_POST["imageX"] + $diffW);
    $_POST["imageY"] = ($rotated_height > $height ? $_POST["imageY"] - $diffH : $_POST["imageY"] + $diffH);

}

//calculate the position from the source image if we need to crop and where
//we need to put into the target image.

$dst_x = $src_x = $dst_y = $src_y = 0;

if($_POST["imageX"] > 0){
    $dst_x = abs($_POST["imageX"]);
}else{
    $src_x = abs($_POST["imageX"]);
}
if($_POST["imageY"] > 0){
    $dst_y = abs($_POST["imageY"]);
}else{
    $src_y = abs($_POST["imageY"]);
}

//This fix the page of the image so it crops fine!
$img->setimagepage(0, 0, 0, 0);
//crop the image with the viewed into the viewport
$img->cropImage($viewPortW, $viewPortH, $src_x, $src_y);

//create the viewport to put the cropped image
$viewport = new Imagick();
$viewport->newImage($viewPortW, $viewPortH,'#'.$colorHEX);
$viewport->setImageFormat($ext);
$viewport->setImageColorspace($img->getImageColorspace());
$viewport->compositeImage($img, $img->getImageCompose(), $dst_x, $dst_y);

//crop the selection from the viewport
$viewport->setImagePage(0, 0, 0, 0);
$viewport->cropImage($_POST["selectorW"],$_POST["selectorH"], $selectorX, $selectorY);

$targetFile = 'tmp/test_'.time().".".$ext;
//save the image into the disk
$viewport->writeImage($targetFile);

echo $targetFile;