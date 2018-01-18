<?php namespace core;

class Route {
	public static $ctrl;
	public static $method;

	static public function init(){
		global $index;

		$ctrl = key($_GET);#配置原型

		if($ctrl) self::$ctrl = $ctrl;

		else self::$ctrl = $index ?? 'index';

		$method = current($_GET);#配置方法#$method = $_GET[$ctrl];

		self::$method = $method ? $method : self::$ctrl;

		array_shift($_GET);#dump($_GET);exit;

		return [self::$ctrl,self::$method];
	}

	static public function match($matchList){
		if(!$matchList) return;

		if(array_key_exists(self::$ctrl,$matchList)) {

			if(count($matchList[self::$ctrl])){

				if(in_array(self::$method,$matchList[self::$ctrl])) $match=true;

			} else $match=true;
		}
		
		if(isset($match)) return true;
	}

	static public function url($array){

		if(count($array)) $url = '?'.http_build_query($array);

		return $url;
	}

/*	static public function POST(){#dump($_POST);exit;
		$Re=new Regular;
		foreach($_POST as $postkey => $value) {#echo $postkey.' '.$value.'<br>';
			#if($postkey!='json') $value=addslashes(trim(stripslashes($value)));
			
			$return=$Re->$postkey($value);

			if(!$return) show($postkey);
		}
	}*/
}

/*	
	public function route(){#$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

		$uriArray=explode('/',trim($_SERVER['REQUEST_URI'],'/'));

		if($uriArray[0]) $this->ctrl=$uriArray[0];

		else $this->ctrl = $index ? $index : 'index';

		if(isset($uriArray[1])) $this->action=$uriArray[1];

		else $this->action=$this->ctrl;

		if(isset($uriArray[2])) {
			$array = explode('-',$uriArray[2]);
			$count=count($array);
			
			for($i=0;$i<$count;$i+=2){

				if(isset($array[$i+1]))

					$_GET[$array[$i]]=$array[$i+1];
			}
		}

		$_GET=array_filter($_GET);
	}
*/