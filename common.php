<?php namespace core;

class Common{

	############################SEO######################
	static public function ctrl_Method_Nav($ctrl,$method){
		$db=new \core\DB;

		$Nav = $db->get('nav',[
			'title','back','state'
		],[
			'ctrl'=>$ctrl,'method'=>$method
		]);

		$Nav['title'] = $Nav['title'] ?? $ctrl.' > '.$method;

		$back = $Nav['back'] ?? $ctrl;#dump($Nav);exit;

		$Nav['back']='?'.$back;

		return $Nav;
	}
	############################SEO######################

	static public function avatarRemove($avatarDIR,$filename){

		$avatarFile=UPLOAD.'/'.$avatarDIR.'/'.$filename;

		if(file_exists($avatarFile)) return unlink($avatarFile);
	}

	static public function avatarUpload($avatarDIR,$mk){

		if( isset($_FILES['avatar']) && $_FILES['avatar']['size'] ){

			$theAvatarDIR=UPLOAD.'/'.$avatarDIR;

			if(!is_dir($theAvatarDIR)) exit('路径不存在');

			$P=new \core\Photo;

			return $P->photoUpload($_FILES['avatar'],$theAvatarDIR,$mk);
		}
	}

	static public function localAvatar($avatar,$avatarDIR){
		$avatarDefault=config('avatar','avatarDefault');

		if($avatar) $localAvatar=UPLOAD.'/'.$avatarDIR.'/'. $avatar;
		
		else $localAvatar=UPLOAD.'/'.$avatarDefault;

		return $localAvatar;
	}

	static public function httpAvatar($avatar,$avatarDIR,$default=null){
		$avatarDefault=config('avatar','avatarDefault');

		if($avatar) $httpAvatar=ATTACH.'/'.$avatarDIR.'/'. $avatar;
		
		else $httpAvatar=ATTACH.'/'.$avatarDefault;

		return $httpAvatar;
	}


	static public function photoList($avatarDIR,$mk){

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

	static public function catid2name($Array,$key,$value=null){

		if(array_key_exists($key,$Array)) $value=$Array[$key]['name'];

		else $value = $value ?? '';

		return $value;
	}


	static public function IP(){
		if(getenv('HTTP_CLIENT_IP')){
			$ip=getenv('HTTP_CLIENT_IP');
		}elseif(getenv('HTTP_X_FORWARDED_FOR')){
			$ip=getenv('HTTP_X_FORWARDED_FOR');
		}elseif(getenv('REMOTE_ADDR')){
			$ip=getenv('REMOTE_ADDR');
		}else{
			$ip=$HTTP_SERVER_VARS['REMOTE_ADDR'];
		}
		return $ip;
	}


	static public function AUTH(){#dump($_COOKIE[AUTHKEY]);exit;
		$loginurl=config('url','login');

		if(empty($_COOKIE[AUTHKEY])) Go($loginurl);

		$cookie=new \core\Cookie;

		$authcode = $cookie->authcode($_COOKIE[AUTHKEY], 'DECODE');

		if(!$authcode) show('验证失败,请重新登录',$loginurl,'1');

		$_LIST = LIST($_USER,$_TIME,$_PASSWORD) = explode("\t", $authcode);#DUMP($_LIST);EXIT;

		if($_USER) {

			define('USER',$_USER);

			return $_USER;

			/*$db=new \core\DB;
			$active=$db->get('user','active',[
				'id'=>$_USER
			]);
			if(!$active)*/
		} else {

			exit('COOKIE异常');#判断密码失效#判断时间失效#记录登录时间#访问权限获取与验证
			#$usermod=$M->loginMod($USER);
			#$usermodArray=json_decode($usermod,true);
			#if(!in_array($m,$modArray)) show("无授权访问");
		}
	}
}