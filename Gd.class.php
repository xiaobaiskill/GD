<?php


class Gd
{
	private $img; //图像资源对象
	private $str = '0123456789abcdefghiljkmnpqrstuvwxyz01234567890123456789';

	private $info; //图像信息资源

	/**
	 *获取图片的信息
	 * @param  [type] $image_file [绝对路径图片文件]
	 * @return [type]             [description]
	 */
	public function open($image_file)
	{
		$image_file = realpath($image_file);
		if (file_exists($image_file)) {

			if(!empty($this->img)) $this->destroy($this->img);

			$image_info = getimagesize($image_file);
			$this->info = [
				'width'     => $image_info[0],
				'height'    => $image_info[1],
				'image_type_num'=>$image_info[2],
				'type'      => image_type_to_extension($image_info[2], false), //默认不带点的后缀名
				'mime'      => $image_info['mime'],
				'image_fun' => str_replace('/', 'createfrom', $image_info['mime']),
				'out_fun'   => str_replace('/', '', $image_info['mime'])
			];

			$this->img = $this->info['image_fun']($image_file);
			return true;
		} else {
			throw new \Exception("不存在的图像资源");
			return false;
		}
	}
	//图像资源的宽
	public function width()
	{
		if(empty($this->img)) throw new \Exception("不存在的图像资源");
		return $this->info['width'];
	}
	
	//图像资源的高
	public function height()
	{
		if(empty($this->img)) throw new \Exception("不存在的图像资源");
		return $this->info['height'];
	}

	/**
	 * /图像资源的类型
	 * @param  [type] $bool [是否在后缀名前加一个点   默认false ,不加点]
	 * @return [type]       [description]
	 */
	public function type($bool=false)
	{
		if(empty($this->img)) throw new \Exception("不存在的图像资源");
		$image_type = $this->info['type'];
		if($bool)
		{
			$image_type = '.' . $image_type;
		}
		return $image_type;
	}

	public function save()
	{

	}

	//添加文字水印
	public function text($text, $font, $size, $color = '#00000000', 
        $locate = 'LT', $offset = 0, $angle = 0)
	{

		imagettftext(image, size, angle, x, y, color, fontfile, text)
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
	public function thumb($dst_w,$dst_h,$equality =false,$file_name =null,$path='image/save')
	{
		$width =$this->width();
		$height = $this->height();

		//等比例 ;
		if($equality)
		{
			$ratio_orig = $width/$height;
			if($dst_w/$dst_h > $ratio_orig)
			{
				$dst_w = $dst_h * $ratio_orig;
			} else{
				$dst_h = $dst_w/$ratio_orig;
			}
		}
		$dst_img=imagecreatetruecolor($dst_w, $dst_h);
		imagecopyresampled($dst_img, $this->img, 0, 0, 0, 0, $dst_w, $dst_h, $width, $height);

		
		$path_file = $this->createImageName($file_name,$path);
		$this->info['out_fun']($dst_img,dirname(__FILE__).'/'.$path_file);
		$this->destroy($dst_img);
		return $path_file;
	}

	//图片水印
	public function water()
	{
		
	}

	//销毁图片资源
	public function destroy($image)
	{
		if(!empty($image)) imagedestroy($image);
	}

	/**
	 * 生成图片的文件名
	 * @param  [type] $name [description]
	 * @param  string $path [description]
	 * @return [type]       [description]
	 */
	private function createImageName($name = null, $path = 'image/save')
	{
		if(file_exists($path)){

			$name = is_null($name) ? substr(str_shuffle($this->str), 0, 13) : $name;
			return ltrim($path,'/') .'/' . $name . $this->type(true);
		}else{
			throw new \Exception("Error Processing Request", 1);
		}
	}
	/**
	 * 
	 * @param  [type] $locate [位置]
	 * @param  [type] $other  [其他事物的宽高  [width,height]]
	 * @return [type]         [description]
	 */
	private function imageLocate($locate,$other=array())
	{
		if(empty($other)){
			throw new \Exception("水印事物没有指定宽高");
		}
		$width = $this->width();
		$height = $this->height();

		try{
			switch ($locate) {
				case 'LT':
					$x = 0;
					$y = 0;
					break;
				case 'LM':
					$x = 0;
					$y = 0;
					break;
				case 'LB':
					$x = 0;
					$y = 0;
					break;
				case 'T':
					$x = 0;
					$y = 0;
					break;
				case 'M':
					$x = 0;
					$y = 0;
					break;
				case 'B':
					$x = 0;
					$y = 0;
					break;	
				case 'RT':
					$x = 0;
					$y = 0;
					break;
				case 'RM':
					$x = 0;
					$y = 0;
					break;
				case 'RB':
					$x = 0;
					$y = 0;
					break;
				default:
					
					break;
			}

			return array($x,$y);
		}catch(Exception $e){
			throw new \Exception($e);
		}
	}

	public function __destruct()
	{
		$this->destroy($this->img);

	}
}

function dd($data, $isDump = false)
{
	echo '<pre>';
	$isDump ? var_dump($data) : print_r($data);
	die;
}




$GD    = new Gd();
$image = 'image/3.jpeg';

$GD->open(dirname(__FILE__) . '/' . $image);
$GD->thumb(300,200,true);
