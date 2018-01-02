<?php
require_once 'Gd.class.php';

$GD         = new Gd();
$image_file = 'image/1.jpg';
$GD->open($image_file);

$image1 = $GD->thumb(300, 200, true);
$GD->outImage($image1);

$image = $GD->water('image/4.png',90);
// $GD->outPut($image);
$GD->outImage($image,'water','image/water');

$image = $GD->text('jmz', '2.ttf', '35', [179, 55, 132], 25, 'LM', 0, 0);
// $GD->outPut($image);
$GD->outImage($image, 'jmz', 'image/text');

$GD->outImage($GD->thumb(50, 40, true),'50-40');