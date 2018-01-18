<?php namespace core;#$array=get_class_methods($C);dump($array);exit;

class We{
	#当类初始化时，自动引入相关文件 PATH_SEPARATOR DIRECTORY_SEPARATOR
	#当不用require载入类文件时,通过include_path装载类
	static public function loading($nsClass){#echo '<br>class---->'.$nsClass;#exit;
		$nsClass = str_replace('\\',DIRECTORY_SEPARATOR,$nsClass);
		$array=explode(DIRECTORY_SEPARATOR,$nsClass);
		$Class=array_pop($array);#$class=strtolower(end($array));PHP类名不区分大小写
		$ns=current($array);
		if($ns==__NAMESPACE__) {
			spl_autoload($Class);
		} else {
			SET_INCLUDE_PATH(APP.DIRECTORY_SEPARATOR.$ns);#spl_autoload_extensions('.php');

			spl_autoload($Class);#必须显试调用spl_autoload函数,类重启类文件自动查找装载

			restore_include_path();
		}
		#if(isset($classMap[$class]))return true;#好像无效
	}

	static public function get($key){
		
		if(isset($_GET[$key])) $var = $_GET[$key];

		return addslashes(trim($var)) ?? NULL;
	}

	static public function post($key){
		
		if(isset($_POST[$key])) $var = $_POST[$key];

		return addslashes(trim($var)) ?? NULL;
	}

	/*
		*json方式输出数据
		*@parm integer $code状态吗
		*@parm string $message消息
		*@parm array data数据
	 */
	static public function json($code=0,$message='',$data=[]){

		if(!is_numeric($code)) return;

		$result=[
			'code'=>$code,
			'message'=>$message,
			'data'=>$data,
		];

		echo json_encode($result);
		exit;
	}

	static public function error($code){
		$errno=(0-$code);

		$errmsg=self::ERRMAP[$code] ?? 'undefined error code';

		return [$errno,$errmsg];
	}

/*	static public function loads($class){
		$arr=explode('\\',$class);
		$ns=$arr[0];
		$class = strtolower(end($arr));

		if($ns=='Ctrl') $file=APP."/Ctrl/".$class.'.php';
		else if($ns=='Model') $file=APP."/Model/".$class.'.php';
		else $file=CORE.'/'.$class.'.php';
		if(is_file($file)) require_once $file;
		else exit("找不到$class");
	}*/

	static public function AUTH(){
		global $login;

		if(empty($_COOKIE[AUTHKEY])) Go($login);

		$cookie=new \core\Cookie;

		$authcode = $cookie->authcode($_COOKIE[AUTHKEY], 'DECODE');#dump($_COOKIE[AUTHKEY]);exit;
		if(!$authcode) show('验证失败,请重新登录',$login,'1');

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

	static public function run(){#global $R;
		#$R=new Route;
		#$ctrl = Route::$ctrl;#变量
		#$method = Route::$method;#方法变量#require_once APP.'/auth.php';#写入ROUTE
		#if( class_exists('\Ctrl\Before') ) {#\Ctrl\Before::}
	}
}

/*

// 或者，自 PHP 5.3.0 起可以使用一个匿名函数
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.class.php';
});

    public function twig($file) {

        $filepath=APP.'/View/'.$file;

        if(is_file($filepath)){#print_r($this->assign);exit;#extract($this->assign);

            Twig_Autoloader::register();

            $loader = new Twig_Loader_Filesystem(APP.'/View');

            $twig = new Twig_Environment($loader, [
                'cache' => CORE.'/twig',
                'debug'=>DEBUG
            ]);

            $template = $twig->V($file);

            $template->display($this->assign);

            exit;
        }
    }
*/
