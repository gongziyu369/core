<?php namespace core;

class Config{

	public $thepath;
	static public $MAP=[];

	static public function get($file,$key){

		if($key) return self::KEY($file,$key);

		else return self::FILE($file);
	}

	static public function FILE($file){
		/*
		1 判断配置文件是否存在
		2 判断配置是否存在
		3 缓存配置 直接返回
		*/
		#dump(self::$MAP);

		if(isset(self::$MAP[$file])) return self::$MAP[$file];#echo 'here ready';exit;

		$appfile=APP.'/config/'.$file.'.php';
		
		if(is_file($appfile)) {

			$filepath=$appfile;

		} else {
			$corefile=CORE.'/config/'.$file.'.php';

			if(is_file($corefile)) $filepath=$corefile;

			else exit('NO_CONFIG_FILE'.$file);
		}

		$array=include $filepath;

		self::$MAP[$file]=$array;

		return $array;
	}

	static public function KEY($file,$key){

		if(isset(self::$MAP[$file])) {######如果已经加载则直接返回

			if(isset(self::$MAP[$file][$key])) return self::$MAP[$file][$key];

		} else {####否则从文件夹中加载

			$appfile	= APP.'/config/'.$file.'.php';
			$corefile	= CORE.'/config/'.$file.'.php';

			$filepath=is_file($appfile) ? $appfile : $corefile;

			if(is_file($filepath)){

				$array=include $filepath;

				if(isset($array[$key])){

					self::$MAP[$file]=$array;

					return $array[$key];

				} else exit('没有这个配置项'.$key);

			} else exit('找不到配置文件');
		}
	}

	static public function gets($name,$file,$private=false){
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