<?php namespace core;

class Conf{
	public $thepath;
	static public $MAP=[];

	static public function file($file){

		if(isset(self::$MAP[$file])){#echo 'here ready';exit;
			return self::$MAP[$file];
		}

		$appfile=APP.'/config/'.$file.'.php';
		
		if(is_file($appfile)) {

			$filepath=$appfile;

		} else {
			$corefile=CORE.'/config/'.$file.'.php';

			if(is_file($corefile)) $filepath=$corefile;

			else exit('找不到配置文件'.$file);
		}

		$conf=include $filepath;

		self::$MAP[$file]=$conf;

		return $conf;

	}

	static public function all($file,$private=false){

		if(isset(self::$MAP[$file])){
			#echo 'here ready';exit;
			return self::$MAP[$file];

		} else {

			if($private) $filepath=APP.'/config/'.$file.'.php';
			else $filepath=CORE.'/config/'.$file.'.php';

			if(is_file($filepath)){

				$conf=include $filepath;

				self::$MAP[$file]=$conf;

				return $conf;

			} else exit('找不到配置文件'.$file);
		}
	}


	static public function get($name,$file,$private=false){
		/*
		1 判断配置文件是否存在
		2 判断配置是否存在
		3 缓存配置 直接返回
		*/
		#dump(self::$MAP);

		if(isset(self::$MAP[$file])) {######如果已经加载则直接返回

			if(isset(self::$MAP[$file][$name])) return self::$MAP[$file][$name];

		} else {####否则从文件夹中加载

			if($private) $filepath=APP.'/config/'.$file.'.php';
			else $filepath=CORE.'/config/'.$file.'.php';

			if(is_file($filepath)){

				$array=include $filepath;

				if(isset($array[$name])){

					self::$MAP[$file]=$array;

					return $array[$name];

				} else exit('没有这个配置项'.$name);

			} else exit('找不到配置文件');
		}

	}
}