<?php
require_once 'Gd.class.php';

$GD         = new Gd();
$image_file = 'image/1.jpg';
$GD->open($image_file);


// $GD->outImage($GD->thumb(300, 200, true));
$image = $GD->text('jmz', '2.ttf', '25', $color = [179, 55, 132], 25, $locate = 'T', 0, 0);
// $GD->outPut($image);
$GD->outImage($image, null, 'image/text');
$image1 = $GD->thumb(300, 200, true);
$GD->outImage($image1);

$image = $GD->water('image/4.png',90);
// $GD->outPut($image);
$GD->outImage($image,null,'image/water');

$GD->outImage($GD->thumb(50, 40, true));