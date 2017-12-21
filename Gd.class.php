<?php


class Gd
{
	private $img; //图像资源对象

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

	//添加文字
	public function text()
	{

	}
	//生成缩略图
	public function thumb($dst_w,$dst_h)
	{
		$dst_img=imagecreatetruecolor($dst_w, $dst_h);
		imagecopyresampled($dst_img, $this->img, dst_x, dst_y, src_x, src_y, $dst_w, $dst_h, $this->width(), $this->height())
	}

	//水印
	public function water()
	{
		
	}

	public function destroy($image)
	{
		if(!empty($image)) imagedestroy($image);
	}
	public function __destruct()
	{
		if(!empty($this->img)){
			$this->destroy($this->img);
		}
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
echo $GD->type();
