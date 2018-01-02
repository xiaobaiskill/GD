<?php

class Gd
{
	private $img; //图像资源对象
	private $img_path;
	private $str = '0123456789abcdefghiljkmnpqrstuvwxyz01234567890123456789';
	private $info; //图像信息资源

	/**
	 * 获取图片的信息
	 * @param  [type] $image_file [绝对路径图片文件]
	 * @return [type]             [description]
	 */

	public function getImageInfo($image_file)
	{
		if (is_file($image_file) && $image_info = @getimagesize($image_file)) {
			$info = [
				'width'          => $image_info[0],
				'height'         => $image_info[1],
				'image_type_num' => $image_info[2],
				'type'           => image_type_to_extension($image_info[2], false), //默认不带点的后缀名
				'mime'           => $image_info['mime'],
				'image_fun'      => str_replace('/', 'createfrom', $image_info['mime']),
				'out_fun'        => str_replace('/', '', $image_info['mime'])
			];
			return $info;
		}
		return false;
	}

	/**
	 * 打开一张图像
	 * @param  [type] $image_file [description]
	 * @return [type]             [description]
	 */
	public function open($image_file)
	{
		$image_file = realpath($image_file);
		if ($image_info = $this->getImageInfo($image_file)) {
			if (!empty($this->img)) {
				$this->destroy($this->img);
			}
			$this->img_path = $image_file;
			$this->info     = $image_info;
			$this->getImage();
			return true;
		} else {
			throw new \Exception("不存在的图像资源");
			return false;
		}
	}

	/**
	 * 获取图像
	 * @return [type] [description]
	 */
	private function getImage()
	{
		$this->img = is_resource($this->img) ? $this->img : $this->info['image_fun']($this->img_path);
		return $this->img;
	}
	//图像资源的宽
	public function width()
	{
		if (empty($this->img)) {
			throw new \Exception("不存在的图像资源");
		}

		return $this->info['width'];
	}

	//图像资源的高
	public function height()
	{
		if (empty($this->img)) {
			throw new \Exception("不存在的图像资源");
		}

		return $this->info['height'];
	}

	/**
	 * 图像资源的后缀类型
	 * @param  [type] $bool [是否在后缀名前加一个点   默认false ,不加点]
	 * @return [type]       [description]
	 */
	public function type($bool = false)
	{
		if (empty($this->img)) {
			throw new \Exception("不存在的图像资源");
		}

		$image_type = $this->info['type'];
		if ($bool) {
			$image_type = '.' . $image_type;
		}
		return $image_type;
	}

	/**
	 * 获取文字的宽高
	 * @param  [type] $size 		[文字大小]
	 * @param  [type] $angle 		[角度]
	 * @param  [type] $font_file 	[文字类的文件]
	 * @param  [type] $text 		[文字]
	 * @return [array]      		[[width,height]]
	 */
	public function imagettfbbox($size, $angle, $font_file, $text)
	{
		$box    = @imagettfbbox($size, $angle, $font_file, $text);
		$width  = abs($box[4] - $box[0]);
		$height = abs($box[5] - $box[1]);
		return [$width, $height];
	}

	/*
	 * 添加文字水印
	 * @param  [type] $text 		[文字]
	 * @param  [type] $font_file 	[将字体放入tffs目录下，直接使用引用的字体文件名]
	 * @param  [type] $size 		[文字大小,使用前将文字先放入tffs中]
	 * @param  [type] $color 		[颜色，三原色]
	 * @param  [type] $alpha 		[文字透明度，0完全不透明 ，127完全透明]
	 * @param  [type] $locate 		[文字所在位置  LT左上角 LM左中 LB左下 T中上 M中间 B中下 RT右上 RM右中 RB右下]
	 * @param  [type] $offset 		[距离 单数的话，左右上下的间距。数组的话[lr_padding,tb_padding]【左右间距，上下间距】]
	 * @param  [type] $angle 		[角度]
	 * */
	public function text($text, $font = '1.ttf', $size = 25, $color = [0, 0, 0], $alpha = 0, $locate = 'LT', $offset = 0, $angle = 0)
	{
		$image     = $this->getImage();
		$color     = imagecolorallocatealpha($image, $color[0], $color[1], $color[2], $alpha);
		$font_file = dirname(__FILE__) . '/tffs/' . $font;
		if (!is_file($font_file)) {
			throw new \Exception("该字体文件不存在");
		}
		$text_arr = $this->imagettfbbox($size, $angle, $font_file, $text);
		if (is_array($offset) && !empty($offset)) {
			list($lr_padding, $tb_padding) = $offset;
		} else {
			$lr_padding = $tb_padding = $offset;
		}
		list($x, $y) = $this->imageLocate($locate, $text_arr, $lr_padding, $tb_padding);
		@imagettftext($image, $size, $angle, $x, $y + $text_arr[1], $color, $font_file, $text);
		return $image;
	}

	/**
	 * 生成缩略图
	 * @param  [type]  $dst_w     [最大宽]
	 * @param  [type]  $dst_h     [最大高]
	 * @param  boolean $equality  [是否等比  ，默认不等比]
	 * @param  [type]  $file_name [文件名]
	 * @param  string  $path      [保存路径]
	 * @return [type]             [description]
	 */
	public function thumb($dst_w, $dst_h, $equality = false)
	{
		$width  = $this->width();
		$height = $this->height();
		$image  = $this->getImage();
		//等比例 ;
		if ($equality) {
			$ratio_orig = $width / $height;
			if ($dst_w / $dst_h > $ratio_orig) {
				$dst_w = $dst_h * $ratio_orig;
			} else {
				$dst_h = $dst_w / $ratio_orig;
			}
		}
		$dst_img = imagecreatetruecolor($dst_w, $dst_h);
		@imagecopyresampled($dst_img, $image, 0, 0, 0, 0, $dst_w, $dst_h, $width, $height);
		return $dst_img;
	}

	/**
	 * /图片水印
	 * @param  [type]  $image_file [水印图片文件路径]
	 * @param  integer $alpha      [透明度 0 ~100  0:完全透明]
	 * @param  string  $locate     [位置   lt lm lb t m b rt rm rb]
	 * @param  integer $offset     [距离 单数的话，左右上下的间距。数组的话[lr_padding,tb_padding]【左右间距，上下间距】]
	 * @return [type]              [图片资源]
	 */
	public function water($image_file, $alpha = 0, $locate = 'LT', $offset = 0)
	{
		$width  = $this->width();
		$height = $this->height();
		$dst_im = $this->getImage();
		$file   = realpath($image_file);

		if ($image_info = $this->getImageInfo($file)) {
			$image = $image_info['image_fun']($file);

			if (is_array($offset) && !empty($offset)) {
				list($lr_padding, $tb_padding) = $offset;
			} else {
				$lr_padding = $tb_padding = $offset;
			}

			list($x, $y) = $this->imageLocate($locate, [$image_info['width'], $image_info['height']], $lr_padding, $tb_padding);

			@imagecopymerge($dst_im, $image, $x, $y, 0, 0, $image_info['width'], $image_info['height'], $alpha);
			$this->destroy($image);
			$this->img = $this->getImage();
			return $dst_im;
		} else {
			throw new \Exception($image_file . "图片资源路径不正确");
			return false;
		}
	}

	/**
	 * 安排文字或者图片的所在位置
	 * @param  [type] $locate [位置]
	 * @param  [type] $other  [其他事物的宽高  [width,height]]
	 * @param  [type] $left [左右边距]
	 * @right  [type] $left [上下边距]
	 * */
	private function imageLocate($locate, $other = [], $lr_padding = 0, $tb_padding = 0)
	{
		$locate = strtoupper($locate);
		if (empty($other)) {
			throw new \Exception("水印事物没有指定宽高");
		}
		$width  = $this->width();
		$height = $this->height();

		try {
			switch ($locate) {
				case 'LT':
					$x = $lr_padding;
					$y = $tb_padding;
					break;
				case 'LM':
					$x = $lr_padding;
					$y = ($height - $other[1]) / 2;
					break;
				case 'LB':
					$x = $lr_padding;
					$y = ($height - $other[1]) - $tb_padding;
					break;
				case 'T':
					$x = ($width - $other[0]) / 2;
					$y = $tb_padding;
					break;
				case 'M':
					$x = ($width - $other[0]) / 2;
					$y = ($height - $other[1]) / 2;
					break;
				case 'B':
					$x = ($width - $other[0]) / 2;
					$y = ($height - $other[1]) - $tb_padding;
					break;
				case 'RT':
					$x = ($width - $other[0]) - $lr_padding;
					$y = $tb_padding;
					break;
				case 'RM':
					$x = ($width - $other[0]) - $lr_padding;
					$y = ($height - $other[1]) / 2;
					break;
				case 'RB':
					$x = ($width - $other[0]) - $lr_padding;
					$y = ($height - $other[1]) - $tb_padding;
					break;
				default:
					$x = $lr_padding;
					$y = $tb_padding;
					break;
			}
			return [$x, $y];
		} catch (Exception $e) {
			throw new \Exception($e);
		}
	}


	//保存图片，返回图片路径
	public function outImage($image, $file_name = null, $path = 'image/save')
	{
		$path_file = $this->createImageName($file_name, $path);
		$this->info['out_fun']($image, dirname(__FILE__) . '/' . $path_file);
		$this->destroy($image);
		return $path_file;
	}

	/**
	 * 生成图片的文件名
	 * @param  [type] $name [description]
	 * @param  string $path [description]
	 * @return [type]       [description]
	 */
	private function createImageName($name = null, $path = 'image/save')
	{
		$path = iconv("UTF-8", "GBK", $path);
		if (!file_exists($path)) {
			mkdir ($path,0777,true);
		}
		$name = is_null($name) ? substr(str_shuffle($this->str), 0, 13) : $name;
		return ltrim($path, '/') . '/' . $name . $this->type(true);
	}

	//输出图片
	public function outPut($image)
	{
		header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		header("content-type: " . $this->info['mime']);
		$this->info['out_fun']($image);
		imagedestroy($image);
	}

	//销毁图片资源
	public function destroy($image)
	{
		is_resource($image) && imagedestroy($image);
	}

	public function __destruct()
	{
		$this->destroy($this->img);
	}
}
