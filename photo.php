<?php namespace core;

class Photo{
	public $quality='80';
	public $width_max='800';
	#public $height_max='500';

	/**
	* desription 判断是否gif动画
	* @param sting $image_file图片路径
	* @return boolean t 是 f 否
	*/
	public function check_gifcartoon($image_file){

	    $fp = fopen($image_file,'rb');
	    $image_head = fread($fp,1024);
	    fclose($fp);
	    return preg_match("/".chr(0x21).chr(0xff).chr(0x0b).'NETSCAPE2.0'."/",$image_head)?false:true;
	}


	public function photoUpload($FILE,$uploadDIR,$filename=null){
		#if(!$FILE['size']) show('图片为空');

		$getimagesize=getimagesize($FILE['tmp_name']);#获取图片属性

		$suffix=$this->mine2suffix($getimagesize['mime']);#查询图片后缀

		$filename= $filename ?? TIME.rand(1000,9999);#图片文件名

		$entity=$uploadDIR.'/'.$filename.'.'.$suffix;#图片存储路径

		$this->compress($FILE['tmp_name'],$entity);#图片压缩上传

		return $filename.'.'.$suffix;
	}

	 
	/**
	* desription 压缩图片
	* @param sting $imgsrc 图片路径
	* @param string $imgdst 压缩后保存路径
	*/
	 
 	public function compress($imgsrc,$imgdst){

 		$dirname=dirname($imgdst);

 		if(!is_dir($dirname)) mkdir($dirname,0755,true);

	    list($width,$height,$type)=getimagesize($imgsrc);

	    if($width<=$this->width_max) {

	    	move_uploaded_file($imgsrc,$imgdst);

	    	return true;
	    }

	    $width_new = $width > $this->width_max ? $this->width_max : $width;

		$height_new=floor($width_new*$height/$width);

	    switch($type){
	      case 1:
	        $giftype=$this->check_gifcartoon($imgsrc);
	        if($giftype){
	          #header('Content-Type:image/gif');
	          $image_wp=imagecreatetruecolor($width_new, $height_new);
	          $image = imagecreatefromgif($imgsrc);
	          imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $width_new, $height_new, $width, $height);
	          imagejpeg($image_wp, $imgdst,$this->quality);//75代表的是质量、压缩图片容量大小
	          imagedestroy($image_wp);
	        } else {
	        	move_uploaded_file($imgsrc,$imgdst);
	        }
	        break;
	      case 2:
	        #header('Content-Type:image/jpeg');
	        $image_wp=imagecreatetruecolor($width_new, $height_new);
	        $image = imagecreatefromjpeg($imgsrc);
	        imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $width_new, $height_new, $width, $height);
	        imagejpeg($image_wp, $imgdst,$this->quality);//75代表的是质量、压缩图片容量大小
	        imagedestroy($image_wp);
	        break;
	      case 3:
	        #header('Content-Type:image/png');
	        $image_wp=imagecreatetruecolor($width_new, $height_new);
	        $image = imagecreatefrompng($imgsrc);
	        imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $width_new, $height_new, $width, $height);
	        imagejpeg($image_wp, $imgdst,$this->quality);//75代表的是质量、压缩图片容量大小
	        imagedestroy($image_wp);
	        break;
	    }
	    return true;
  	}

  	public function mine2suffix($mine){#

	  	switch($mine){
			case "image/gif": 
				$suffix='gif';break;
			case "image/jpeg": 
				$suffix='jpg';break;			
			case "image/png": 
				$suffix='png';break;
			case "image/bmp": 
				$suffix='bmp';break;
			default:
				$suffix='photo';break;
	  	}

	  	return $suffix;
	}


	public function photoList($avatarDIR,$mk){

		$theAvatarDIR=UPLOAD.'/'.$avatarDIR.'/'. $mk;

		if(is_dir($theAvatarDIR)) {

			foreach (new \DirectoryIterator($theAvatarDIR) as $fileInfo) {
			    if($fileInfo->isDot()) continue;
			    #echo $fileInfo->getFilename() . "<br>\n";
			    $fileArray[]=$fileInfo->getFilename();
			}

			if(!isset($fileArray)) return;
			
			natsort($fileArray);

			$i=0;
			foreach($fileArray as $file) {
				LIST($width,$height)=getimagesize($theAvatarDIR.'/'.$file);
				/*$newweight=600;
				$height=floor($newweight*$height/$width);
				$width=$newweight;*/
				$file=iconv('GBK','UTF-8',$file);
				$filename=pathinfo($file)['filename'];

				$photoList[$i]['src']=ATTACH.'/'.$avatarDIR.'/'.$mk.'/'.$file;
				$photoList[$i]['alt']=$filename;
				$photoList[$i]['file']=$file;
				$photoList[$i]['width']="{$width}px";
				$photoList[$i]['height']="{$height}px";
				$i++;
			}
		}

		return @$photoList;
	}
}