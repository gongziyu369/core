<?php namespace core;

class myQrcode{
/*
	public function delQrcode($USERID){
		global $userQrcode;

		$mylogo="$userQrcode/$USERID.png";
	
		if(file_exists($mylogo)) unlink($mylogo);

	}

	function agentAvatar($AGENT){
		global $images;

		$logoPhoto="$images/logo.png";

		return $logoPhoto;
	}

	function getQrcode($USERID){
		global $qrcode;

		return "$qrcode/$USERID.png";
	}
*/
	

	public function makeQrcode($qrcodeFile,$qrcodeValue,$qrcodeLogo){

		include_once CORE.'/lib/phpqrcode/phpqrcode.php';

		$errorCorrectionLevel='H';//容错级别

		$matrixPointSize=6;//生成图片大小

		#生成二维码图片#QRcode::png("$HTTPHOST_LXWY/sign.php?recommend={$USERID}");exit;
		QRcode::png($qrcodeValue,$qrcodeFile,$errorCorrectionLevel,$matrixPointSize,2);

		$this->makeQrcodeLogo($qrcodeFile,$qrcodeLogo);
	}

	public function makeQrcodeLogo($qrcodeFile,$qrcodeLogo){

		if($qrcodeLogo !== FALSE) {
			#$qrcode=imagecreatefromstring(file_get_contents($qrcodeFile));
			#$logo=imagecreatefromjpeg($qrcodeLogo);

			$qrcode=imagecreatefrompng($qrcodeFile);

			$logo=imagecreatefromstring(file_get_contents($qrcodeLogo));

			$QR_width=imagesx($qrcode);//二维码图片宽度
			$QR_height=imagesy($qrcode);//二维码图片高度

			$logo_width=imagesx($logo);//logo图片宽度
			$logo_height=imagesy($logo);//logo图片高度

			$logo_qr_width=$QR_width/6;###最终LOGO的宽度
			$scale=$logo_width/$logo_qr_width;
			$logo_qr_height=$logo_height/$scale;###最终LOGO的高度
			$from_width=($QR_width-$logo_qr_width)/2;

			$QR_true=imagecreatetruecolor($QR_width, $QR_height);

			imagecopyresampled($QR_true, $qrcode, 0, 0, 0, 0,$QR_width,$QR_height,$QR_width,$QR_height);
	        #header('Content-Type:image/png');imagepng($QR_true);exit;

			imagecopyresampled($QR_true, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);//重新组合图片并调整大小

			imagepng($QR_true,$qrcodeFile,9);//输出图片文件
			//Header("Content-type: image/png");ImagePng($qrcode);exit;//动态输出图片
		}
		return;
	}
}