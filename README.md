# GD
ct-GD  图片处理类

Gd.class.php  

首先使用open()
之后可以使用text(),water(),thumb()
这三个方法的返回的都是图像
可以将返回的图像保存outImage()  或者直接输出outPut()








test.php-----------------前期准备使用的文件

Gd.class.php-------------后期封装好的类
    ->$img---------------图像
    ->$img_path----------原始图片
    ->$str---------------随机字符串
    ->$info--------------图片信息
    ->getImageInfo-------获取图片的信息以及可使用该图片的函数
    ->open---------------打开一张图像，将资源至于类属性中
    ->getImage-----------重新创建图片的图像
    ->width
    ->height
    ->type---------------图片的后缀名
    ->imagettfbbox-------获取文字的信息
    ->text---------------添加水印文字-------》
    ->thumb--------------缩略图------------》          三个返回的都是图片的图像
    ->water--------------图片水印----------》
    ->createImageName----创建一个新图片的名称
    ->imageLocate--------目标事物的开始坐标
    ->outImage-----------保存图片
    ->outPut-------------输出图片
    ->destroy------------销毁图片
   

index.php----------------调用并使用封装好的类
