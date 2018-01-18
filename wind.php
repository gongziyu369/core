<?php namespace core;#$array=get_class_methods($C);dump($array);exit;

class Wind{
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
		}#if(isset($classMap[$class]))return true;#好像无效
	}

	static public function get($key){
		
		if(isset($_GET[$key])) {

			$var = $_GET[$key];

			return addslashes(trim($var));
		}
	}

	static public function post($key){
		
		if(isset($_POST[$key])) {

			$var = $_POST[$key];

			return addslashes(trim($var));
		}
	}

	static public function token(){
		return;
		if(empty($_POST['token'])) self::msg('POST_TOKEN_EMPTY');

		else if(empty($_SESSION['token'])) self::msg('SESSION_TOKEN_EMPTY');

		else if( $_SESSION['token'] == $_POST['token'] ) {
			
			unset($_SESSION['token']);

			unset($_POST['token']);
		}
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

	static public function bug($key,$file=null){

		$bugConfig=Config::file('bug');

		if(isset($bugConfig[$key])) $bug=$bugConfig[$key];

		else $bug = $key.'<br>undefined bug key';

		echo $bug.$file;exit;
	}

	public static function Go($url) {

		$url=$url ?? REFERER;

		header("Location:{$url}");exit;
	}

	static public function lang($key){

		$Lang=Config::file('lang');

		return $Lang[$key] ?? $key;
	}

	static public function msg($key,$url=NULL,$timeout=NULL){

		$msg=self::lang($key);#$this->msg?

		$url=$url ?? self::post('then');

		if($url) {
			$do="location.href='$url'";
		} else {
			$do="history.go(-1);";
			$url="javascript:history.go(-1);";
		}

	    $viewConfig=config('view');extract($viewConfig);
		$timeout = $timeout ?? $setTimeout;

		include self::view(__FUNCTION__);exit;
	}

	/*视图*/
	static public function view($tpl) {

	    $V=new View($tpl);

	    $parse=$V->checkRefresh(false);

	    if($parse) $V->parse();

	    return $V->cacheFile;
	}

	static public function error($code){
		$errno=(0-$code);

		$errmsg=self::ERRMAP[$code] ?? 'undefined error code';

		return json_encode([$errno,$errmsg]);
	}

	static public function Node($node){

		$db=new \core\DB;

		$dataArray=$db->debug()->select('node',[
			'node','parent','name','sort'
		],[
			'AND'=>['parent'=>$node,'state'=>1],
			'ORDER'=>['sort'=>'DESC'],
		]);
dump($dataArray);exit;
		return $dataArray;
	}

}

/*	
	static public function loads($class){
		$arr=explode('\\',$class);
		$ns=$arr[0];
		$class = strtolower(end($arr));

		if($ns=='Ctrl') $file=APP."/Ctrl/".$class.'.php';
		else if($ns=='Model') $file=APP."/Model/".$class.'.php';
		else $file=CORE.'/'.$class.'.php';
		if(is_file($file)) require_once $file;
		else exit("找不到$class");
	}

	static public function run(){#global $R;
		$R=new Route;
		$ctrl = Route::$ctrl;#变量
		$method = Route::$method;#方法变量#require_once APP.'/auth.php';#写入ROUTE
		if( class_exists('\Ctrl\Before') ) {#\Ctrl\Before::}
	}

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
