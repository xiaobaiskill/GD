<?php


$image_info = getimagesize('image/1.jpeg');


$image = imagecreatefromjpeg('image/2.jpeg');

$src_image = imagecreatefromjpeg("image/1.jpeg");

imagecopymerge($image,$src_image,0,0,0,0,200,200,80);

header('content-type:image/jpeg');
imagejpeg($image);

imagedestroy($src_image);

imagedestroy($image);
/*
$image = imagecreatetruecolor(50,50);

$src_image = imagecreatefromjpeg('image/1.jpeg');

$image_info = getimagesize('image/1.jpeg');

imagecopyresampled($image,$src_image,0,0,0,0,50,50,$image_info[0],$image_info[0]);

imagejpeg($image,'image/test.jpeg');

imagedestroy($image);
imagedestroy($src_image);*/