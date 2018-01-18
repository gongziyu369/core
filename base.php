<?php namespace core;

class base{

	protected $M;

	public function __construct($ctrl=''){#spl_autoload_register("\core\WE::loadMODEL");

		if($ctrl) {
			$this->ctrl=$ctrl;

			$nsModel = '\\Model\\'.ucfirst($ctrl);

			$this->M = new $nsModel();#dump($this->M);exit;

			$this->table=$this->M->table ?? NULL;
			$this->primary=$this->M->primary ?? NULL;
		}
	}

	public function __call($method,$arguments){#var_dump($arguments)

		exit($method .'NO_AUTH_METHODby_ctrl_call');
	}

	public function __get($key){

		exit($key .'NOT_EXISTS_by_ctrl_get');
	}

	#public function __destruct(){}

	protected function urls($string){
		$array=explode('/',$string);
		dump($array);exit;
	}

	protected function url($array){

		if(count($array)) return '?'.http_build_query($array);
	}

	protected function Go($url) {

		if(is_array($url)) $url=$this->array2url($url);

		$url=$url ?? REFERER;

		header("Location:{$url}");exit;
	}
}

/* public function newModel($ctrl){
		#$this->newModel($ctrl);
		global $M;

        #数据库模型文件加载
		$modelfile = APP."/Model/$ctrl.php";

		if(file_exists($modelfile)) require_once $modelfile;

		$modelClass="{$ctrl}Model";

		$M=new $modelClass($ctrl);

		$this->table=$M->table;

		$this->primary=$M->primary;
	}*/