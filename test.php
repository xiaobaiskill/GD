<?php



//图片缩略图

$image_file   = 'image/3.jpeg';
$path = dirname(__FILE__).'/'.$image_file;

$width  = 70;
$height = 200;

// $width1  = 50;
// $height1 = 50;

$image_info = getimagesize($path);
$image_ext  = image_type_to_extension($image_info[2]);

$image_fun = str_replace('/', 'createfrom', $image_info['mime']);
$out_fun   = str_replace('/', '', $image_info['mime']);

$image     = imagecreatetruecolor($width, $height);
//$image1    = imagecreatetruecolor($width1, $height1);
$src_image = $image_fun($path);

imagecopyresampled($image, $src_image, 0, 0, 0, 0, $width, $height, $image_info[0], $image_info[1]);
//imagecopyresampled($image1, $src_image, 0, 0, 0, 0, $width1, $height1, $image_info[0], $image_info[1]);

$out_fun($image, 'image/copy_' . $width . $image_ext);
//$out_fun($image1, 'image/copy_' . $width1 . $image_ext);

imagedestroy($image);
imagedestroy($src_image);

 

/*
//文字水印

$image_file = 'image/1.jpg';

$path = dirname(__FILE__) . '/' .$image_file;

$image_info = getimagesize($path);
$image_ext  = image_type_to_extension($image_info[2]);
$image_fun = str_replace('/', 'createfrom', $image_info['mime']);
$out_fun	= str_replace('/', '', $image_info['mime']);

$image = $image_fun($path);

$color = imagecolorallocatealpha($image, 50, 86, 100, 10);

//imagestring($image, 5, 0, 0, 'AFFGGDFSD', $color);

imagettftext($image, 30, 0, 0, 50, $color, dirname(__FILE__) . '/' . 'tffs/2.ttf', '我在测试呢');

$out_fun($image,'image/water_image'.$image_ext);
imagedestroy($image);
 */

/*
//图片水印

$image_vice = 'image/1.jpeg';

$image_main = 'image/2.gif';

$image_logo = imagecreatefromjpeg($image_vice);

list($width, $height) = getimagesize($image_vice);

$image = imagecreatefromgif($image_main);

imagecopymerge($image, $image_logo, 0, 0, 0, 0, $width, $height, 50);

header('Content-type:image/gif');

imagegif($image);

imagedestroy($image);

imagedestroy($image_logo);*/








/*

图片水印
$image_info = getimagesize('image/1.jpeg');


$image = imagecreatefromjpeg('image/2.jpeg');

$src_image = imagecreatefromjpeg("image/1.jpeg");

imagecopymerge($image,$src_image,0,0,0,0,200,200,80);

header('content-type:image/jpeg');
imagejpeg($image);

imagedestroy($src_image);

imagedestroy($image);
*/





/*

	缩略图
$image = imagecreatetruecolor(50,50);

$src_image = imagecreatefromjpeg('image/1.jpeg');

$image_info = getimagesize('image/1.jpeg');

imagecopyresampled($image,$src_image,0,0,0,0,50,50,$image_info[0],$image_info[0]);

imagejpeg($image,'image/test.jpeg');

imagedestroy($image);
imagedestroy($src_image);

*/
