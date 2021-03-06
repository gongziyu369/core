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

		if(!$authcode) msg('PLEASE_LOGIN',$loginurl,'1');

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



	static public function isMobile() { 
	    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
	        return true;
	    } 
	    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	    if (isset ($_SERVER['HTTP_VIA'])) { 
	        // 找不到为flase,否则为true
	        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
	    } 
	    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
	    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
	        $clientkeywords = ['nokia',
	            'sony',
	            'ericsson',
	            'mot',
	            'samsung',
	            'htc',
	            'sgh',
	            'lg',
	            'sharp',
	            'sie-',
	            'philips',
	            'panasonic',
	            'alcatel',
	            'lenovo',
	            'iphone',
	            'ipod',
	            'blackberry',
	            'meizu',
	            'android',
	            'netfront',
	            'symbian',
	            'ucweb',
	            'windowsce',
	            'palm',
	            'operamini',
	            'operamobi',
	            'openwave',
	            'nexusone',
	            'cldc',
	            'midp',
	            'wap',
	            'mobile'
	        ]; 
	        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
	        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
	            return true;
	        } 
	    }
	    // 协议法，因为有可能不准确，放到最后判断
	    if (isset ($_SERVER['HTTP_ACCEPT'])) {
	        // 如果只支持wml并且不支持html那一定是移动设备
	        // 如果支持wml和html但是wml在html之前则是移动设备
	        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
	            return true;
	        }
	    }
	    return false;
	}


}